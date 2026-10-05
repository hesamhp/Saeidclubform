<?php
/* ============================================================
 *  ۳. پردازشگر درخواست‌ها (handlers.php)
 *  شامل: مسیریابی، ورود/خروج مدیر، دانلود فایل، عملیات تولد و فرم‌های ۱ تا ۵
 * ============================================================ */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$route = trim($_SERVER['PATH_INFO'] ?? '', '/');
if ($route === '' && strpos($path, $scriptName) === 0) {
    $route = trim(substr($path, strlen($scriptName)), '/');
}

$isPanel = isset($_GET['panel']) || $route === 'panel';
$slug    = isset($_GET['form']) ? trim((string)$_GET['form']) : null;

if ($slug === null && $route !== '' && !$isPanel && isset($forms[$route])) {
    $slug = $route;
}
if ($slug !== null && !isset($forms[$slug])) {
    $slug = null;
}

$isActiveForm = ($slug !== null && $forms[$slug]['active']);
$homeUrl      = $baseUrl . '?lang=' . rawurlencode($lang);
$panelUrl     = $baseUrl . '?panel=1&lang=' . rawurlencode($lang);

$isAdmin    = !empty($_SESSION['coach_auth']);
$loginError = false;

/* ── ورود / خروج مدیر ── */
if ($isPanel) {
    if (isset($_GET['logout'])) {
        unset($_SESSION['coach_auth']);
        header('Location: ' . $panelUrl);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_user'], $_POST['login_pass'])) {
        $u = (string)$_POST['login_user'];
        $p = (string)$_POST['login_pass'];
        if (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
            $_SESSION['coach_auth'] = true;
            $isAdmin = true;
            appLog('info', 'Admin login succeeded.', ['username' => $u]);
            header('Location: ' . $panelUrl);
            exit;
        }
        appLog('warning', 'Admin login failed.', ['username' => $u]);
        $loginError = true;
    }
}

/* ── دانلود فایل (فقط مدیر) ── */
if (isset($_GET['download'])) {
    if (empty($_SESSION['coach_auth'])) {
        appLog('warning', 'Unauthorized download attempt.');
        http_response_code(403);
        echo 'برای دانلود فایل، ابتدا به عنوان مدیر وارد شوید.';
        exit;
    }
    $parts = explode('/', $_GET['download'], 2);
    $slugD = $parts[0] ?? '';
    $fileD = basename($parts[1] ?? '');
    if (isset($forms[$slugD]) && in_array(strtolower(pathinfo($fileD, PATHINFO_EXTENSION)), ['json', 'txt'], true)) {
        $full = submissionsDir($slugD) . DIRECTORY_SEPARATOR . $fileD;
        if (is_file($full)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="submission"; filename*=UTF-8\'\'' . rawurlencode($fileD));
            header('Content-Length: ' . filesize($full));
            readfile($full);
            exit;
        }
    }
    appLog('warning', 'Requested download file was not found or invalid.', ['form' => $slugD, 'extension' => strtolower(pathinfo($fileD, PATHINFO_EXTENSION))]);
    http_response_code(404);
    echo 'فایل پیدا نشد.';
    exit;
}

/* ── عملیات دفترچه تولد در پنل ── */
if ($isPanel && $isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bd_action'])) {
    $action = $_POST['bd_action'];
    if ($action === 'done' && !empty($_POST['person_id'])) {
        if (markBirthdayDone($_POST['person_id'], $currentJalali[0])) {
            appLog('info', 'Birthday notification marked as done.');
            header('Location: ' . $panelUrl . '&bd=done');
        } else {
            appLog('error', 'Birthday notification could not be marked as done.');
            header('Location: ' . $panelUrl . '&bd=invalid');
        }
        exit;
    }
    if ($action === 'delete' && !empty($_POST['person_id'])) {
        if (deletePerson($_POST['person_id'])) {
            appLog('info', 'Birthday record deleted by admin.');
            header('Location: ' . $panelUrl . '&bd=deleted');
        } else {
            appLog('error', 'Birthday record could not be deleted.');
            header('Location: ' . $panelUrl . '&bd=invalid');
        }
        exit;
    }
    if ($action === 'add') {
        $pName = trim((string)($_POST['p_name'] ?? ''));
        $pY    = (int)($_POST['p_year']  ?? 0);
        $pM    = (int)($_POST['p_month'] ?? 0);
        $pD    = (int)($_POST['p_day']   ?? 0);
        if ($pName !== '' && $pY && $pM && $pD && !($pM > 6 && $pD > 30)) {
            if (addPerson($pName, $pY, $pM, $pD, 'ثبت دستی مدیر')) {
                header('Location: ' . $panelUrl . '&bd=added');
            } else {
                appLog('warning', 'Manual birthday record was not added.', ['reason' => 'duplicate_or_storage_failure']);
                header('Location: ' . $panelUrl . '&bd=invalid');
            }
        } else {
            appLog('warning', 'Invalid manual birthday input.');
            header('Location: ' . $panelUrl . '&bd=invalid');
        }
        exit;
    }
}

/* ── حذف پاسخ توسط مدیر ── */
if ($isPanel && $isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'], $_POST['delete_form'])) {
    $slugX = $_POST['delete_form'];
    $fileX = basename($_POST['delete_file']);
    if (isset($forms[$slugX]) && in_array(strtolower(pathinfo($fileX, PATHINFO_EXTENSION)), ['json', 'txt'], true)) {
        $full = submissionsDir($slugX) . DIRECTORY_SEPARATOR . $fileX;
        if (is_file($full)) {
            if (unlink($full)) {
                appLog('info', 'Submission deleted by admin.', ['form' => $slugX]);
                header('Location: ' . $panelUrl . '&deleted=1');
                exit;
            }
            appLog('error', 'Submission could not be deleted.', ['form' => $slugX]);
        }
    }
    appLog('warning', 'Invalid submission deletion request.', ['form' => $slugX]);
    header('Location: ' . $panelUrl . '&deleted=0');
    exit;
}

$errors  = [];
$success = null;
$bmiText = '---';

/* ── پردازش فرم ۱ ── */
if (!$isPanel && $slug === 'form1' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname      = trim($_POST['fullname']      ?? '');
    $bdY           = (int)($_POST['bd_year']      ?? 0);
    $bdM           = (int)($_POST['bd_month']     ?? 0);
    $bdD           = (int)($_POST['bd_day']       ?? 0);
    $country       = trim($_POST['country']       ?? '');
    $job           = trim($_POST['job']           ?? '');
    $height        = trim($_POST['height']        ?? '');
    $weight        = trim($_POST['weight']        ?? '');
    $jobActivity   = trim($_POST['jobActivity']   ?? '');
    $sitHours      = trim($_POST['sitHours']      ?? '');
    $sleepHours    = trim($_POST['sleepHours']    ?? '');
    $stress        = trim($_POST['stress']        ?? '');
    $sportHistory  = trim($_POST['sportHistory']  ?? '');
    $sportTypes    = trim($_POST['sportTypes']    ?? '');
    $trainDays     = trim($_POST['trainDays']     ?? '');
    $trainPlace    = $_POST['trainPlace'] ?? [];
    $goals         = $_POST['goals'] ?? [];
    $threeMonthGoal= trim($_POST['threeMonthGoal'] ?? '');
    $hasPain       = trim($_POST['hasPain']       ?? '');
    $painLocation  = trim($_POST['painLocation']  ?? '');
    $painLevel     = trim($_POST['painLevel']     ?? '');
    $painTriggers  = trim($_POST['painTriggers']  ?? '');
    $medicalHistory= $_POST['medicalHistory'] ?? [];
    $meals         = trim($_POST['meals']         ?? '');
    $favFoods      = trim($_POST['favFoods']      ?? '');
    $dislikedFoods = trim($_POST['dislikedFoods'] ?? '');
    $supplements   = trim($_POST['supplements']   ?? '');
    $supplementNames = trim($_POST['supplementNames'] ?? '');
    $equipment     = trim($_POST['equipment']     ?? '');
    $docAnswers = [];
    foreach ($documents as $key => $label) {
        $docAnswers[$key] = trim($_POST[$key] ?? '');
    }

    if ($fullname === '')       $errors[] = 'نام و نام خانوادگی را وارد کنید.';
    if ($bdY === 0 || $bdM === 0 || $bdD === 0) $errors[] = 'تاریخ تولد را کامل انتخاب کنید.';
    elseif ($bdM > 6 && $bdD > 30)              $errors[] = 'تاریخ تولد معتبر نیست.';
    elseif (jalaliIsFuture($bdY, $bdM, $bdD))   $errors[] = 'تاریخ تولد نمی‌تواند در آینده باشد.';
    if ($country === '')        $errors[] = 'کشور محل زندگی را وارد کنید.';
    if ($job === '')            $errors[] = 'شغل را وارد کنید.';
    if ($height === '')         $errors[] = 'قد را وارد کنید.';
    if ($weight === '')         $errors[] = 'وزن را وارد کنید.';
    if ($jobActivity === '')    $errors[] = 'نوع فعالیت شغلی را انتخاب کنید.';
    if ($sitHours === '')       $errors[] = 'میانگین ساعت نشستن روزانه را وارد کنید.';
    if ($sleepHours === '')     $errors[] = 'میانگین خواب شبانه را وارد کنید.';
    if ($stress === '')         $errors[] = 'سطح استرس را انتخاب کنید.';
    if ($sportHistory === '')   $errors[] = 'سابقه ورزش را وارد کنید.';
    if ($sportTypes === '')     $errors[] = 'نوع ورزش‌های قبلی را وارد کنید.';
    if ($trainDays === '')      $errors[] = 'تعداد روزهای تمرین در هفته را وارد کنید.';
    if (empty($trainPlace))     $errors[] = 'حداقل یک محل تمرین را انتخاب کنید.';
    if (empty($goals))          $errors[] = 'حداقل یک هدف اصلی را انتخاب کنید.';
    if ($threeMonthGoal === '') $errors[] = 'هدف سه ماه آینده را بنویسید.';
    if ($hasPain === '')        $errors[] = 'وضعیت درد یا محدودیت حرکتی را مشخص کنید.';
    if ($hasPain === 'بله') {
        if ($painLocation === '')  $errors[] = 'چون درد دارید، «محل درد» را وارد کنید.';
        if ($painTriggers === '')  $errors[] = 'چون درد دارید، «حرکات تشدیدکننده درد» را وارد کنید.';
    }
    if (empty($medicalHistory)) $errors[] = 'در بخش سابقه پزشکی حداقل یک مورد را انتخاب کنید.';
    if ($meals === '')          $errors[] = 'تعداد وعده‌های غذایی را وارد کنید.';
    if ($favFoods === '')       $errors[] = 'غذاهای مورد علاقه را وارد کنید.';
    if ($dislikedFoods === '')  $errors[] = 'غذاهایی که مصرف نمی‌کنید را وارد کنید.';
    if ($supplements === '')    $errors[] = 'وضعیت مصرف مکمل را مشخص کنید.';
    if ($supplements === 'بله' && $supplementNames === '') {
        $errors[] = 'چون مکمل مصرف می‌کنید، نام مکمل‌ها را بنویسید.';
    }
    if ($equipment === '')      $errors[] = 'وضعیت تجهیزات تمرینی را انتخاب کنید.';
    foreach ($documents as $key => $label) {
        if (($docAnswers[$key] ?? '') === '') {
            $errors[] = 'وضعیت «' . $label . '» را مشخص کنید.';
        }
    }

    if (is_numeric($height) && is_numeric($weight) && $height > 0) {
        $hMeter  = $height / 100;
        $bmi     = round($weight / ($hMeter * $hMeter), 1);
        $bmiText = $bmi . ' (' . bmiLabel($bmi) . ')';
    }

    if (empty($errors)) {
        $birthdateJ = sprintf('%04d/%02d/%02d', $bdY, $bdM, $bdD);
        [$bgY, $bgM, $bgD] = jalaaliToGregorian($bdY, $bdM, $bdD);
        $birthdateG = sprintf('%04d-%02d-%02d', $bgY, $bgM, $bgD);

        $answers = [
            'نام و نام خانوادگی'                  => $fullname,
            'تاریخ تولد (شمسی)'                   => $birthdateJ,
            'تاریخ تولد (میلادی)'                 => $birthdateG,
            'سن (محاسبه خودکار)'                  => calcAgeText($birthdateG),
            'کشور محل زندگی'                      => $country,
            'شغل'                                 => $job,
            'قد'                                  => $height !== '' ? $height . ' سانتی‌متر' : '',
            'وزن'                                 => $weight !== '' ? $weight . ' کیلوگرم' : '',
            'شاخص توده بدنی (BMI)'                => $bmiText,
            'نوع شغل از نظر فعالیت بدنی'          => $jobActivity,
            'میانگین ساعت نشستن روزانه'           => $sitHours !== '' ? $sitHours . ' ساعت' : '',
            'میانگین خواب شبانه'                  => $sleepHours !== '' ? $sleepHours . ' ساعت' : '',
            'سطح استرس'                           => $stress,
            'سابقه ورزش'                          => $sportHistory,
            'نوع ورزش‌های قبلی'                   => $sportTypes,
            'تعداد روزهای تمرین در هفته'          => $trainDays !== '' ? $trainDays . ' روز' : '',
            'محل تمرین'                           => $trainPlace,
            'اهداف اصلی'                          => $goals,
            'هدف سه ماه آینده'                    => $threeMonthGoal,
            'درد یا محدودیت حرکتی فعلی'           => $hasPain,
            'محل درد'                             => ($hasPain === 'بله') ? $painLocation : 'ندارد',
            'شدت درد (۰ تا ۱۰)'                   => ($hasPain === 'بله') ? $painLevel : 'ندارد',
            'حرکات تشدیدکننده درد'                => ($hasPain === 'بله') ? $painTriggers : 'ندارد',
            'سابقه پزشکی'                         => $medicalHistory,
            'تعداد وعده‌های غذایی'                => $meals !== '' ? $meals . ' وعده' : '',
            'غذاهای مورد علاقه'                   => $favFoods,
            'غذاهایی که مصرف نمی‌کند'             => $dislikedFoods,
            'مکمل مصرف می‌کند؟'                   => $supplements,
            'نام مکمل‌ها'                         => ($supplements === 'بله') ? $supplementNames : 'ندارد',
            'وضعیت تجهیزات'                       => $equipment,
        ];
        foreach ($documents as $key => $label) {
            $answers[$label] = $docAnswers[$key];
        }

        $savedFile = saveSubmission($slug, $fullname, $answers);
        if ($savedFile !== false) {
            addPerson($fullname, $bdY, $bdM, $bdD, $forms[$slug]['title']);
            $success = $savedFile;
            $_POST   = [];
        } else {
            $errors[] = 'خطا در ذخیره فایل! مطمئن شوید پوشه پروژه قابل نوشتن است.';
        }
    }
}

/* ── پردازش فرم ۲ ── */
if (!$isPanel && $slug === 'form2' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $g = fn($k) => trim($_POST[$k] ?? '');
    $a = fn($k) => (array)($_POST[$k] ?? []);

    $fullname   = $g('fullname');
    $phone      = $g('phone');
    $bdY        = (int)$g('bd_year')  ?: 0;
    $bdM        = (int)$g('bd_month') ?: 0;
    $bdD        = (int)$g('bd_day')   ?: 0;
    $height     = $g('height');
    $weight     = $g('weight');
    $freeHours  = $g('freeHours');
    $howKnow    = $g('howKnow');
    $goals2     = $a('goals2');
    $sportHist2 = $g('sportHist2');
    $jobStyle   = $g('jobStyle');
    $sitHours   = $g('sitHours');
    $walkHours  = $g('walkHours');
    $sleepHours = $g('sleepHours');
    $stressLvl  = $g('stressLvl');
    $diseases   = $a('diseases');
    $medications= $g('medications');
    $surgery    = $g('surgery');
    $restriction= $g('restriction');
    $painAreas  = $a('painAreas');
    $painLevel  = $g('painLevel');
    $painStart  = $g('painStart');
    $treatment  = $g('treatment');
    $difficulties = $a('difficulties');
    $sleepQuality = $g('sleepQuality');
    $smoking    = $g('smoking');
    $alcohol    = $g('alcohol');
    $water      = $g('water');
    $fruits     = $g('fruits');
    $diet       = $g('diet');
    $services   = $a('services');
    $startMonth = $g('startMonth');
    $oneProblem = $g('oneProblem');

    if ($fullname === '')     $errors[] = 'نام و نام خانوادگی را وارد کنید.';
    if ($phone === '')        $errors[] = 'شماره تماس را وارد کنید.';
    elseif (!preg_match('/^[0-9\-\+\s]{8,16}$/', $phone)) $errors[] = 'شماره تماس معتبر نیست.';
    if ($bdY === 0 || $bdM === 0 || $bdD === 0) $errors[] = 'تاریخ تولد را کامل انتخاب کنید.';
    elseif ($bdM > 6 && $bdD > 30)              $errors[] = 'تاریخ تولد معتبر نیست.';
    elseif (jalaliIsFuture($bdY, $bdM, $bdD))   $errors[] = 'تاریخ تولد نمی‌تواند در آینده باشد.';
    if ($height === '')       $errors[] = 'قد را وارد کنید.';
    if ($weight === '')       $errors[] = 'وزن را وارد کنید.';
    if ($freeHours === '')    $errors[] = 'ساعت‌های آزاد برای تمرین را وارد کنید.';
    if ($howKnow === '')      $errors[] = 'نحوه آشنایی را انتخاب کنید.';
    if (empty($goals2))       $errors[] = 'حداقل یک هدف مراجعه را انتخاب کنید.';
    if ($sportHist2 === '')   $errors[] = 'سابقه ورزشی را انتخاب کنید.';
    if ($jobStyle === '')     $errors[] = 'وضعیت شغلی را انتخاب کنید.';
    if ($sitHours === '')     $errors[] = 'میزان نشستن روزانه را وارد کنید.';
    if ($walkHours === '')    $errors[] = 'میزان پیاده‌روی روزانه را وارد کنید.';
    if ($sleepHours === '')   $errors[] = 'میزان خواب شبانه را وارد کنید.';
    if (empty($diseases))     $errors[] = 'در بخش بیماری‌ها حداقل یک مورد را انتخاب کنید.';
    if ($medications === '')  $errors[] = 'وضعیت مصرف دارو یا مکمل را بنویسید.';
    if ($surgery === '')      $errors[] = 'سابقه جراحی را بنویسید.';
    if ($restriction === '')  $errors[] = 'محدودیت پزشک را بنویسید.';
    if (empty($painAreas))    $errors[] = 'در بخش درد یا آسیب حداقل یک مورد را انتخاب کنید.';
    $hasPain2 = (!empty($painAreas) && !in_array('ندارم', $painAreas, true));
    if ($hasPain2) {
        if ($painStart === '') $errors[] = 'زمان شروع درد را بنویسید.';
        if ($treatment === '') $errors[] = 'مشخص کنید تحت درمان هستید یا خیر.';
    }
    if (empty($difficulties)) $errors[] = 'در بخش فعالیت‌های دشوار حداقل یک مورد را انتخاب کنید.';
    if ($sleepQuality === '') $errors[] = 'کیفیت خواب را انتخاب کنید.';
    if ($smoking === '')      $errors[] = 'وضعیت مصرف دخانیات را انتخاب کنید.';
    if ($alcohol === '')      $errors[] = 'وضعیت مصرف مشروبات الکلی را انتخاب کنید.';
    if ($diet === '')         $errors[] = 'رژیم غذایی خود را بنویسید.';
    if (empty($services))     $errors[] = 'حداقل یک خدمت را انتخاب کنید.';
    if ($startMonth === '')   $errors[] = 'مشخص کنید قصد شروع در همین ماه را دارید یا خیر.';
    if ($oneProblem === '')   $errors[] = 'به سؤال مشکل جسمانی سه ماه آینده پاسخ دهید.';

    if (is_numeric($height) && is_numeric($weight) && $height > 0) {
        $hM      = $height / 100;
        $bmi     = round($weight / ($hM * $hM), 1);
        $bmiText = $bmi . ' (' . bmiLabel($bmi) . ')';
    }

    if (empty($errors)) {
        $birthdateJ = sprintf('%04d/%02d/%02d', $bdY, $bdM, $bdD);
        [$bgY, $bgM, $bgD] = jalaaliToGregorian($bdY, $bdM, $bdD);
        $birthdateG = sprintf('%04d-%02d-%02d', $bgY, $bgM, $bgD);

        $answers = [
            'نام و نام خانوادگی'                          => $fullname,
            'شماره تماس'                                  => $phone,
            'تاریخ تولد (شمسی)'                           => $birthdateJ,
            'تاریخ تولد (میلادی)'                         => $birthdateG,
            'سن (محاسبه خودکار)'                          => calcAgeText($birthdateG),
            'قد'                                          => $height !== '' ? $height . ' سانتی‌متر' : '',
            'وزن'                                         => $weight !== '' ? $weight . ' کیلوگرم' : '',
            'شاخص توده بدنی (BMI)'                        => $bmiText,
            'ساعت‌های آزاد برای تمرین'                    => $freeHours,
            'نحوه آشنایی با سعید کلاب'                    => $howKnow,
            'اهداف اصلی مراجعه'                           => $goals2,
            'سابقه ورزشی (روز در هفته)'                   => $sportHist2,
            'وضعیت شغلی و سبک زندگی'                      => $jobStyle,
            'میزان نشستن روزانه'                          => $sitHours !== '' ? $sitHours . ' ساعت' : '',
            'پیاده‌روی روزانه'                            => $walkHours !== '' ? $walkHours . ' ساعت' : '',
            'خواب شبانه'                                  => $sleepHours !== '' ? $sleepHours . ' ساعت' : '',
            'سطح استرس (۰ تا ۱۰)'                         => $stressLvl,
            'بیماری‌های تشخیص‌داده‌شده'                   => $diseases,
            'دارو یا مکمل مصرفی'                          => $medications,
            'سابقه جراحی'                                 => $surgery,
            'محدودیت اعلام‌شده توسط پزشک/فیزیوتراپ'       => $restriction,
            'نواحی درد یا آسیب'                           => $painAreas,
            'شدت درد (۰ تا ۱۰)'                           => $hasPain2 ? $painLevel : 'ندارد',
            'زمان شروع درد'                               => $hasPain2 ? $painStart : 'ندارد',
            'تحت درمان است؟'                              => $hasPain2 ? $treatment : 'ندارد',
            'فعالیت‌های دشوار'                            => $difficulties,
            'کیفیت خواب'                                  => $sleepQuality,
            'مصرف دخانیات'                                => $smoking,
            'مصرف مشروبات الکلی'                          => $alcohol,
            'میزان مصرف آب روزانه (۱ تا ۵)'               => $water,
            'میانگین مصرف میوه و سبزی (۱ تا ۵)'           => $fruits,
            'رژیم غذایی خاص'                              => $diet,
            'خدمات مورد نظر'                              => $services,
            'قصد شروع در همین ماه'                        => $startMonth,
            'مهم‌ترین مشکل جسمانی برای رفع در ۳ ماه آینده' => $oneProblem,
        ];
        $savedFile = saveSubmission($slug, $fullname, $answers);
        if ($savedFile !== false) {
            addPerson($fullname, $bdY, $bdM, $bdD, $forms[$slug]['title']);
            $success = $savedFile;
            $_POST   = [];
        } else {
            $errors[] = 'خطا در ذخیره فایل! مطمئن شوید پوشه پروژه قابل نوشتن است.';
        }
    }
}

/* ── پردازش فرم ۳ ── */
if (!$isPanel && $slug === 'form3' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $g = fn($k) => trim($_POST[$k] ?? '');
    $a = fn($k) => (array)($_POST[$k] ?? []);

    $fullname     = $g('fullname');
    $changes      = $a('changes');
    $otherChange  = $g('otherChange');
    $progress     = $g('progress');
    $goalCloser   = $g('goalCloser');
    $quality = [];
    foreach ($f3_quality as $key => $label) {
        $quality[$key] = $g($key);
    }
    $reasons      = $a('reasons');
    $keepThing    = $g('keepThing');
    $improveThing = $g('improveThing');
    $oneChange    = $g('oneChange');
    $npsScore     = $g('npsScore');
    $recommend    = $g('recommend');

    if ($fullname === '')     $errors[] = 'نام و نام خانوادگی را وارد کنید.';
    if (empty($changes) && $otherChange === '') $errors[] = 'حداقل یک تغییر را انتخاب یا یادداشت کنید.';
    if ($goalCloser === '')   $errors[] = 'مشخص کنید به هدف اولیه نزدیک‌تر شده‌اید یا خیر.';
    if (empty($reasons))      $errors[] = 'حداقل یک دلیل برای ادامه همکاری انتخاب کنید.';
    if ($keepThing === '')    $errors[] = 'بنویسید چه چیزی نباید تغییر کند.';
    if ($improveThing === '') $errors[] = 'بنویسید چه چیزی را می‌توانیم بهتر کنیم.';
    if ($oneChange === '')    $errors[] = 'به سؤال یک تغییر پاسخ دهید.';
    if ($recommend === '')    $errors[] = 'میزان تمایل به معرفی را انتخاب کنید.';

    if (empty($errors)) {
        $answers = [
            'نام و نام خانوادگی'        => $fullname,
            'تغییرات احساس‌شده'         => $changes,
            'تغییر دیگر'                => $otherChange,
            'ارزیابی پیشرفت (۰ تا ۱۰)'  => $progress,
            'نزدیک شدن به هدف اولیه'    => $goalCloser,
        ];
        foreach ($f3_quality as $key => $label) {
            $answers[$label . ' (از ۱۰)'] = $quality[$key];
        }
        $nums = array_filter($quality, fn($v) => is_numeric($v));
        $answers['میانگین کیفیت خدمات'] = !empty($nums) ? round(array_sum($nums) / count($nums), 1) . ' از ۱۰' : '';
        $answers['دلایل ادامه همکاری']            = $reasons;
        $answers['چه چیزی نباید تغییر کند']       = $keepThing;
        $answers['چه چیزی را بهتر کنیم']          = $improveThing;
        $answers['اگر فقط یک تغییر ممکن بود']     = $oneChange;
        $answers['امتیاز احتمال معرفی (۰ تا ۱۰)'] = $npsScore;
        $answers['وضعیت معرفی']                   = $recommend;

        $savedFile = saveSubmission($slug, $fullname, $answers);
        if ($savedFile !== false) {
            $success = $savedFile;
            $_POST   = [];
        } else {
            $errors[] = 'خطا در ذخیره فایل! مطمئن شوید پوشه پروژه قابل نوشتن است.';
        }
    }
}

/* ── پردازش فرم ۴ ── */
if (!$isPanel && $slug === 'form4' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $g = fn($key) => trim((string)($_POST[$key] ?? ''));
    $a = fn($key) => array_values(array_filter((array)($_POST[$key] ?? []), 'is_string'));

    $fullname       = $g('fullname');
    $phone          = $g('phone');
    $bdY            = (int)$g('bd_year')  ?: 0;
    $bdM            = (int)$g('bd_month') ?: 0;
    $bdD            = (int)$g('bd_day')   ?: 0;
    $height         = $g('height');
    $weight         = $g('weight');
    $organization   = $g('organization');
    $departmentRole = $g('departmentRole');
    $jobType        = $g('jobType');
    $deskHours      = $g('deskHours');
    $orgGoals       = $a('orgGoals');
    $activityLevel  = $g('activityLevel');
    $trainingHist4  = $g('trainingHist4');
    $sports4        = $a('sports4');
    $diseases4      = $a('diseases4');
    $medications4   = $g('medications4');
    $surgery4       = $g('surgery4');
    $hasPain4       = $g('hasPain4');
    $painAreas4     = $a('painAreas4');
    $painLevel4     = $g('painLevel4');
    $sleepQuality4  = $g('sleepQuality4');
    $stressLevel4   = $g('stressLevel4');
    $smoking4       = $g('smoking4');
    $alcohol4       = $g('alcohol4');
    $raiseArms4     = $g('raiseArms4');

    if ($fullname === '')       $errors[] = 'نام و نام خانوادگی را وارد کنید.';
    if ($phone === '')          $errors[] = 'شماره تماس را وارد کنید.';
    elseif (!preg_match('/^[0-9\-\+\s]{8,16}$/', $phone)) $errors[] = 'شماره تماس معتبر نیست.';
    if ($bdY === 0 || $bdM === 0 || $bdD === 0) $errors[] = 'تاریخ تولد را کامل انتخاب کنید.';
    elseif ($bdM > 6 && $bdD > 30)               $errors[] = 'تاریخ تولد معتبر نیست.';
    elseif (jalaliIsFuture($bdY, $bdM, $bdD))    $errors[] = 'تاریخ تولد نمی‌تواند در آینده باشد.';
    if ($height === '')         $errors[] = 'قد را وارد کنید.';
    if ($weight === '')         $errors[] = 'وزن را وارد کنید.';
    if ($organization === '')   $errors[] = 'نام سازمان یا اداره را وارد کنید.';
    if ($departmentRole === '') $errors[] = 'واحد محل خدمت و سمت سازمانی را وارد کنید.';
    if ($jobType === '')        $errors[] = 'نوع شغل را انتخاب کنید.';
    if ($deskHours === '')      $errors[] = 'میزان نشستن پشت میز را وارد کنید.';
    if (empty($orgGoals))       $errors[] = 'حداقل یک هدف اصلی را انتخاب کنید.';
    if (count($orgGoals) > 3)   $errors[] = 'حداکثر سه هدف اصلی قابل انتخاب است.';
    if ($activityLevel === '')  $errors[] = 'سطح فعالیت فعلی را انتخاب کنید.';
    if ($trainingHist4 === '')  $errors[] = 'سابقه تمرین را انتخاب کنید.';
    if (empty($sports4))        $errors[] = 'حداقل یک نوع ورزش را انتخاب کنید.';
    if (empty($diseases4))      $errors[] = 'در بخش بیماری‌ها حداقل یک مورد را انتخاب کنید.';
    if ($medications4 === '')   $errors[] = 'وضعیت مصرف دارو یا مکمل را بنویسید.';
    if ($surgery4 === '')       $errors[] = 'سابقه جراحی را بنویسید.';
    if ($hasPain4 === '')       $errors[] = 'مشخص کنید در حال حاضر درد دارید یا خیر.';
    if ($hasPain4 === 'بله' && empty($painAreas4)) $errors[] = 'حداقل یک محل درد را انتخاب کنید.';
    if ($hasPain4 === 'بله' && (!is_numeric($painLevel4) || $painLevel4 < 0 || $painLevel4 > 10)) $errors[] = 'شدت درد باید بین 0 تا 10 باشد.';
    if ($sleepQuality4 === '')  $errors[] = 'کیفیت خواب را انتخاب کنید.';
    if (!is_numeric($stressLevel4) || $stressLevel4 < 1 || $stressLevel4 > 5) $errors[] = 'سطح استرس باید بین 1 تا 5 باشد.';
    if ($smoking4 === '')       $errors[] = 'وضعیت مصرف دخانیات را انتخاب کنید.';
    if ($alcohol4 === '')       $errors[] = 'وضعیت مصرف مشروبات الکلی را انتخاب کنید.';
    if ($raiseArms4 === '')     $errors[] = 'مشخص کنید در بالا بردن دست‌ها مشکل دارید یا خیر.';

    if (is_numeric($height) && is_numeric($weight) && (float)$height > 0) {
        $heightM = (float)$height / 100;
        $bmi = round((float)$weight / ($heightM * $heightM), 1);
        $bmiText = $bmi . ' (' . bmiLabel($bmi) . ')';
    }

    if (empty($errors)) {
        $birthdateJ = sprintf('%04d/%02d/%02d', $bdY, $bdM, $bdD);
        [$bgY, $bgM, $bgD] = jalaaliToGregorian($bdY, $bdM, $bdD);
        $birthdateG = sprintf('%04d-%02d-%02d', $bgY, $bgM, $bgD);

        $answers = [
            'نام و نام خانوادگی'          => $fullname,
            'شماره تماس'                  => $phone,
            'تاریخ تولد (شمسی)'           => $birthdateJ,
            'تاریخ تولد (میلادی)'         => $birthdateG,
            'سن (محاسبه خودکار)'          => calcAgeText($birthdateG),
            'قد'                          => $height . ' سانتی‌متر',
            'وزن'                         => $weight . ' کیلوگرم',
            'شاخص توده بدنی (BMI)'        => $bmiText,
            'نام سازمان یا اداره'         => $organization,
            'واحد محل خدمت و سمت سازمانی' => $departmentRole,
            'نوع شغل'                     => $jobType,
            'نشستن پشت میز روزانه'        => $deskHours . ' ساعت',
            'اهداف اصلی (حداکثر ۳)'       => $orgGoals,
            'سطح فعالیت فعلی'             => $activityLevel,
            'سابقه تمرین'                 => $trainingHist4,
            'نوع ورزش'                    => $sports4,
            'بیماری‌های تشخیص‌داده‌شده'   => $diseases4,
            'دارو یا مکمل مصرفی'          => $medications4,
            'سابقه جراحی'                 => $surgery4,
            'در حال حاضر درد دارد؟'       => $hasPain4,
            'محل درد'                     => ($hasPain4 === 'بله') ? $painAreas4 : 'ندارد',
            'شدت درد (۰ تا ۱۰)'           => ($hasPain4 === 'بله') ? $painLevel4 : 'ندارد',
            'کیفیت خواب'                  => $sleepQuality4,
            'سطح استرس (۱ تا ۵)'          => $stressLevel4,
            'مصرف دخانیات'                => $smoking4,
            'مصرف مشروبات الکلی'          => $alcohol4,
            'مشکل در بالا بردن دست‌ها'    => $raiseArms4,
        ];
        $savedFile = saveSubmission($slug, $fullname, $answers);
        if ($savedFile !== false) {
            addPerson($fullname, $bdY, $bdM, $bdD, $forms[$slug]['title']);
            $success = $savedFile;
            $_POST = [];
        } else {
            $errors[] = 'خطا در ذخیره فایل! مطمئن شوید پوشه پروژه قابل نوشتن است.';
        }
    }
}

/* ── پردازش فرم ۵ (کاملاً اختیاری) ── */
if (!$isPanel && $slug === 'form5' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $g = fn($key) => trim((string)($_POST[$key] ?? ''));
    $a = fn($key) => array_values(array_filter((array)($_POST[$key] ?? []), 'is_string'));

    $fullname     = $g('fullname');
    $answers = [
        'نام و نام خانوادگی (اختیاری)'       => $fullname,
        '1. خدمات مورد استفاده'              => $a('services5'),
        '2. مدت عضویت'                       => $g('memberDuration'),
        '3. هدف اصلی'                        => $a('goals5'),
        '4. نمره تجربه کلی (0 تا 10)'        => $g('satOverall'),
        '5. خدمات نسبت به انتظار'            => $g('expect5'),
        '6. دلیل اصلی نمره'                  => $g('scoreReason5'),
        '7. میزان پیشرفت'                    => $g('progress5'),
        '8. مهم‌ترین تغییر مثبت'             => $g('bigChange5'),
        '9. کمک ارزیابی‌ها و گزارش‌ها'       => $g('assess5'),
        '10. آگاهی از هدف برنامه فعلی'       => $g('knowGoal5'),
        '11. تناسب برنامه با هدف و شرایط'    => $g('planFit5'),
        '12. نمره مربی (0 تا 10)'            => $g('satCoach'),
        '13. توجه مربی به اصلاح تکنیک'       => $g('attention5'),
        '14. شناخت مربی از شرایط شما'        => $g('knowsYou5'),
        '15. پیشنهاد بهبود عملکرد مربی'      => $g('coachFix5'),
        '16. پیگیری پیشرفت توسط سیستم'       => $g('sysFollow5'),
        '17. نظارت سعید رمضان‌پور'           => $g('saeedWatch5'),
        '18. کیفیت پاسخ‌گویی تیم'            => $g('teamReply5'),
        '19. کیفیت و تنوع تجهیزات (0 تا 10)' => $g('satEquip'),
        '20. نظافت و نظم محیط (0 تا 10)'     => $g('satClean'),
        '21. اثر شلوغی بر تمرین'             => $g('crowd5'),
        '22. پیشنهاد بهبود امکانات'          => $g('facilityFix5'),
        '23. ارزش خدمات در برابر هزینه'      => $g('valueMoney5'),
        '24. ارزشمندترین بخش خدمات'          => $g('valuedPart5'),
        '25. احتمال تمدید عضویت (0 تا 10)'   => $g('satRenew'),
        '26. دلیل احتمال قطع عضویت'          => $g('quitReason5'),
        '27. نمره پیشنهاد به دیگران (0 تا 10)' => $g('satNps'),
        '28. مهم‌ترین نقطه قوت'              => $g('strength5'),
        '29. مهم‌ترین نقطه ضعف'              => $g('weakness5'),
        '30. پیشنهاد یک تغییر'               => $g('oneChange5'),
    ];

    $savedFile = saveSubmission($slug, ($fullname !== '' ? $fullname : 'ناشناس'), $answers);
    if ($savedFile !== false) {
        $success = $savedFile;
        $_POST   = [];
    } else {
        $errors[] = 'خطا در ذخیره فایل! مطمئن شوید پوشه پروژه قابل نوشتن است.';
    }
}

/* ── جمع‌آوری داده‌های پنل مربی ── */
$panelData = [];
if ($isPanel && $isAdmin) {
    foreach ($forms as $fs => $fm) {
        if (!$fm['active']) continue;
        $dir  = $projectDir . '/submissions/' . $fs;
        $list = [];
        $size = 0;
        $last = null;
        foreach (glob($dir . '/*.json') ?: [] as $p) {
            $raw  = file_get_contents($p);
            $data = ($raw !== false) ? json_decode((string)$raw, true) : null;
            if (!is_array($data)) {
                appLog('error', 'Submission JSON could not be read in admin panel.', ['form' => $fs, 'json_error' => ($raw === false ? 'read_failed' : json_last_error_msg())]);
                $data = [];
            }
            $list[] = [
                'name'    => basename($p),
                'time'    => filemtime($p),
                'size'    => filesize($p),
                'person'  => $data['full_name']    ?? '—',
                'date'    => $data['submitted_at'] ?? date('Y-m-d H:i:s', filemtime($p)),
                'answers' => is_array($data['answers'] ?? null) ? $data['answers'] : [],
            ];
            $size  += filesize($p);
            $last   = max($last ?? 0, filemtime($p));
        }
        usort($list, fn($a, $b) => $b['time'] - $a['time']);
        $panelData[$fs] = ['meta' => $fm, 'files' => $list, 'totalSize' => $size, 'last' => $last ?: null];
    }
}

$bdBoard = ($isPanel && $isAdmin) ? birthdayBoard() : ['today' => [], 'upcoming' => []];
$allPeople = ($isPanel && $isAdmin) ? loadPeople() : [];
$showPainBox = (($_POST['hasPain'] ?? '') === 'بله');
