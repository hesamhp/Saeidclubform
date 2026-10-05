<?php
/* ============================================================
 *  ۱. فایل پیکربندی (config.php)
 *  شامل: تنظیمات منطقه زمانی، لاگ‌گیری، رجیستری فرم‌ها، گزینه‌ها و دیکشنری ترجمه
 * ============================================================ */

date_default_timezone_set('Asia/Tehran');
mb_internal_encoding('UTF-8');

$projectDir = __DIR__;
$logDir     = $projectDir . DIRECTORY_SEPARATOR . 'logs';
$logFile    = $logDir . DIRECTORY_SEPARATOR . 'error.log';
$requestId  = substr(hash('sha256', uniqid('', true) . microtime(true)), 0, 12);

/* ── سیستم مرکزی ثبت خطا در logs/error.log ── */
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
if (is_dir($logDir)) {
    if (!is_file($logDir . DIRECTORY_SEPARATOR . '.htaccess')) {
        @file_put_contents(
            $logDir . DIRECTORY_SEPARATOR . '.htaccess',
            "Deny from all\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n"
        );
    }
    if (!is_file($logDir . DIRECTORY_SEPARATOR . 'index.html')) {
        @file_put_contents($logDir . DIRECTORY_SEPARATOR . 'index.html', '');
    }
}

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', $logFile);

// ثبت ساختاریافته خطا در فایل لاگ
function appLog($level, $message, array $context = []) {
    global $logFile, $requestId;
    $currentLogSize = is_file($logFile) ? @filesize($logFile) : 0;
    if ($currentLogSize !== false && $currentLogSize > 5 * 1024 * 1024) {
        @rename($logFile, dirname($logFile) . DIRECTORY_SEPARATOR . 'error-' . date('Ymd-His') . '.log');
    }
    $record = [
        'time'       => date('Y-m-d H:i:s'),
        'level'      => strtoupper((string)$level),
        'request_id' => $requestId,
        'message'    => (string)$message,
        'request'    => [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'path'   => parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH),
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
        ],
        'context'    => $context,
    ];
    $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($line !== false) {
        $written = @file_put_contents($logFile, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
        if ($written === false) {
            $fallback = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'saeid-club-error.log';
            @file_put_contents($fallback, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}

function phpErrorLabel($severity) {
    $labels = [
        E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING', E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE', E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING', E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING', E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT', E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED', E_USER_DEPRECATED => 'E_USER_DEPRECATED',
    ];
    return $labels[$severity] ?? ('E_' . $severity);
}

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) return false;
    appLog('error', $message, [
        'type' => phpErrorLabel($severity),
        'file' => $file,
        'line' => $line,
    ]);
    return true;
});

set_exception_handler(function ($exception) {
    $safeTrace = [];
    foreach ($exception->getTrace() as $frame) {
        $safeTrace[] = [
            'file' => $frame['file'] ?? null,
            'line' => $frame['line'] ?? null,
            'call' => ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? ''),
        ];
    }
    appLog('critical', $exception->getMessage(), [
        'exception' => get_class($exception),
        'file'      => $exception->getFile(),
        'line'      => $exception->getLine(),
        'trace'     => $safeTrace,
    ]);
    http_response_code(500);
    if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="fa" dir="rtl"><meta charset="utf-8"><title>خطای داخلی</title>'
       . '<body style="background:#09090b;color:#e4e4e7;font-family:sans-serif;text-align:center;padding:80px 20px">'
       . '<h1 style="color:#facc15">خطای داخلی رخ داد</h1>'
       . '<p>شناسه پیگیری: <code>' . htmlspecialchars($GLOBALS['requestId']) . '</code></p>'
       . '</body></html>';
});

register_shutdown_function(function () {
    $last = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];
    if ($last && in_array($last['type'], $fatalTypes, true)) {
        appLog('critical', $last['message'], [
            'type' => phpErrorLabel($last['type']),
            'file' => $last['file'],
            'line' => $last['line'],
        ]);
    }
});

header('Content-Type: text/html; charset=utf-8');
header('X-Request-ID: ' . $requestId);

if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
    appLog('error', 'Session could not be started.');
}

/* ── حساب مدیر پنل ── */
const ADMIN_USER = 'saeid';
const ADMIN_PASS = 'saeid0811';

/* ── آدرس‌ها ── */
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$self       = basename($scriptName);
$base       = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$baseUrl    = ($base !== '' ? $base : '') . '/' . $self;

/* ── زبان ── */
$lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? 'fa';
if (!in_array($lang, ['fa', 'en'], true)) $lang = 'fa';
if (isset($_GET['lang'])) setcookie('lang', $lang, time() + 31536000, '/');
$isEn = ($lang === 'en');

function langUrl($code) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $uri = preg_replace('/([?&])lang=[^&]*/', '$1', $uri);
    $uri = rtrim($uri, '?&');
    return $uri . (strpos($uri, '?') !== false ? '&' : '?') . 'lang=' . $code;
}

/* ── رجیستری فرم‌ها ── */
$forms = [
    'form1' => [
        'title'  => 'فرم شروع همکاری آنلاین',
        'desc'   => 'مشخصات فردی، سبک زندگی، سابقه تمرینی، اهداف، وضعیت جسمانی، تغذیه و مدارک اولیه.',
        'icon'   => 'clipboard-list',
        'active' => true,
    ],
    'form2' => [
        'title'  => 'فرم مشاوره اولیه',
        'desc'   => 'مشخصات تماس، هدف مراجعه، سبک زندگی، سوابق پزشکی، درد و محدودیت، عادات روزانه و انتخاب خدمات سعید کلاب.',
        'icon'   => 'phone-call',
        'active' => true,
    ],
    'form3' => [
        'title'  => 'فرم ارزیابی مسیر اعضای آنلاین',
        'desc'   => 'بررسی تغییرات و پیشرفت، کیفیت خدمات، ارزش همکاری، پیشنهادهای بهبود و میزان تمایل به معرفی.',
        'icon'   => 'chart-line',
        'active' => true,
    ],
    'form4' => [
        'title'  => 'فرم مشاوره اولیه سازمانی',
        'desc'   => 'ارزیابی شرایط شغلی، اهداف، سابقه ورزشی، وضعیت سلامت، درد، خواب و سبک زندگی کارکنان.',
        'icon'   => 'building-2',
        'active' => true,
    ],
    'form5' => [
        'title'  => 'فرم نظرسنجی رضایت اعضای SAEID CLUB',
        'desc'   => 'نظرسنجی ۳۰ سؤالی رضایت اعضا: تجربه حضور، پیشرفت، مربی، پشتیبانی، امکانات، ارزش خدمات و وفاداری.',
        'icon'   => 'clipboard-check',
        'active' => true,
    ],
];

/* ── گزینه‌های فرم ۱ ── */
$jobActivities = ['پشت‌میزنشینی', 'نیمه‌فعال', 'فعال'];
$stressOptions = [
    ['label' => 'کم',    'cls' => 'peer-checked:bg-emerald-500 peer-checked:ring-emerald-500 peer-checked:shadow-emerald-500/30 peer-checked:text-black'],
    ['label' => 'متوسط', 'cls' => 'peer-checked:bg-amber-400   peer-checked:ring-amber-400   peer-checked:shadow-amber-400/30  peer-checked:text-black'],
    ['label' => 'زیاد',  'cls' => 'peer-checked:bg-rose-500    peer-checked:ring-rose-500    peer-checked:shadow-rose-500/30'],
];
$trainPlaces  = ['باشگاه', 'منزل', 'فضای باز', 'سفر / هتل'];
$goalsList = [
    'کاهش چربی', 'افزایش عضله', 'افزایش قدرت', 'اصلاح فرم بدن',
    'کاهش درد و محدودیت حرکتی', 'افزایش انرژی و سلامت عمومی', 'آمادگی ورزشی',
];
$medicalList = ['هیچ‌کدام', 'دیسک کمر', 'دیسک گردن', 'سابقه جراحی', 'آسیب مفصل', 'آسیب عضله', 'سایر'];
$equipmentOptions = [
    'باشگاه می‌روم (به تجهیزات کامل دسترسی دارم)',
    'تجهیزات ندارم، اما تهیه می‌کنم',
    'تجهیزات ندارم',
];
$documents = [
    'photo_front' => 'عکس روبه‌رو',
    'photo_back'  => 'عکس پشت',
    'photo_side'  => 'عکس بغل (کنار)',
    'bodycomp'    => 'بادی کامپوزیشن (ترکیب بدنی)',
    'bloodtest'   => 'آخرین آزمایش خون',
];

/* ── گزینه‌های فرم ۲ ── */
$f2_howKnow = ['اینستاگرام', 'معرفی دوستان', 'گوگل', 'عبوری', 'شاگرد قبلی'];
$f2_goals = [
    'کاهش وزن', 'کاهش چربی شکم', 'افزایش عضله', 'تناسب اندام', 'اصلاح پاسچر',
    'کاهش درد', 'افزایش قدرت', 'افزایش انعطاف‌پذیری', 'افزایش تعادل', 'سلامت عمومی',
    'آماده‌سازی مسابقات', 'استعدادیابی ورزشی', 'برنامه سالمندی (Longevity)', 'آب‌درمانی', 'سایر',
];
$f2_sportHistory = ['هیچ', '1 تا 2 روز', '3 تا 4 روز', '5 روز یا بیشتر'];
$f2_jobStyle     = ['پشت میز', 'ایستاده', 'فعالیت متوسط', 'فعالیت سنگین'];
$f2_diseases     = [
    'هیچ‌کدام', 'فشار خون', 'دیابت', 'بیماری قلبی', 'تیروئید', 'چربی خون',
    'آسم', 'پوکی استخوان', 'آرتروز', 'دیسک گردن', 'دیسک کمر',
];
$f2_painAreas    = ['ندارم', 'کمر', 'گردن', 'زانو', 'شانه', 'لگن', 'مچ پا', 'مچ دست', 'سایر'];
$f2_difficulties = [
    'هیچ‌کدام', 'بالا رفتن از پله', 'دویدن', 'نشستن طولانی', 'ایستادن طولانی',
    'چمباتمه زدن', 'خم شدن', 'بلند کردن اجسام', 'حفظ تعادل', 'بالا بردن دست‌ها',
];
$f2_sleepQuality = [
    'کلاً کم‌خواب هستم', 'بین 6 تا 8 ساعت می‌خوابم',
    'چندین بار در طول شب بیدار می‌شوم', 'بیشتر از 8 ساعت می‌خوابم',
];
$f2_smoking = ['مصرف ندارم', 'سیگار یا قلیان کم', 'سیگار یا قلیان زیاد'];
$f2_alcohol = ['مصرف ندارم', 'کم', 'زیاد'];
$f2_services = [
    'آنالیز کامل بدن', 'تمرین VIP با ترینر اختصاصی',
    'تمرین Premium با سعید رمضان‌پور', 'حرکات اصلاحی', 'آب‌درمانی',
    'برنامه آنلاین', 'هنوز نمی‌دانم، مشاوره می‌خواهم', 'نامه از پزشک دارم',
];
$f2_startMonth = ['بله، همین ماه', 'خیر', 'هنوز مطمئن نیستم'];

/* ── گزینه‌های فرم ۳ ── */
$f3_changes = [
    'کاهش وزن / تغییر فرم بدن', 'افزایش قدرت', 'افزایش انرژی',
    'بهبود انعطاف و حرکت', 'کاهش درد', 'افزایش نظم تمرینی',
];
$f3_goalProgress = ['بله', 'تا حدی', 'هنوز نه'];
$f3_quality = [
    'q_program' => 'کیفیت برنامه تمرینی',
    'q_diet'    => 'کیفیت رژیم غذایی',
    'q_custom'  => 'میزان اختصاصی بودن برنامه',
    'q_support' => 'کیفیت پشتیبانی',
];
$f3_reasons = [
    'نتیجه‌ای که گرفته‌ام', 'برنامه اختصاصی', 'دانش و تجربه مربی',
    'پیگیری و پشتیبانی', 'اصلاح تکنیک و جلوگیری از اشتباهات', 'راحتی تمرین از هر کشور و شرایط',
];
$f3_recommend = ['حتماً معرفی می‌کنم', 'احتمالاً', 'کم', 'هیچ'];

/* ── گزینه‌های فرم ۴ ── */
$f4_jobTypes = ['اداری', 'مدیریتی', 'عملیاتی', 'خدماتی', 'سایر'];
$f4_goals = [
    'کاهش وزن', 'کاهش چربی شکمی', 'افزایش عضله', 'بهبود سلامت', 'کاهش درد',
    'اصلاح پاسچر', 'افزایش آمادگی جسمانی', 'پیشگیری از آسیب', 'افزایش کیفیت زندگی', 'سایر',
];
$f4_activityLevels = [
    'اصلاً ورزش نمی‌کنم', 'گاهی ورزش می‌کنم', 'هفته‌ای 1 تا 2 جلسه',
    'هفته‌ای 3 جلسه یا بیشتر', 'ورزشکار هستم',
];
$f4_trainingHistory = [
    'بدون سابقه', 'کمتر از 3 ماه', '3 ماه تا 1 سال', 'بیشتر از 1 سال', 'حرفه‌ای',
];
$f4_sports = ['هیچ‌کدام', 'پیاده‌روی', 'بدنسازی', 'شنا', 'دویدن', 'فوتبال', 'کوهنوردی', 'سایر'];
$f4_diseases = [
    'هیچ‌کدام', 'فشار خون', 'دیابت', 'بیماری قلبی', 'چربی خون',
    'آسم', 'پوکی استخوان', 'تیروئید', 'بیماری‌های مفصلی',
];
$f4_painAreas = ['گردن', 'شانه', 'آرنج', 'مچ دست', 'کمر', 'لگن', 'زانو', 'مچ پا', 'سایر'];
$f4_sleepQuality = [
    'کلاً کم‌خواب هستم', 'بین 6 تا 8 ساعت می‌خوابم',
    'چندین بار در طول شب بیدار می‌شوم', 'بیشتر از 8 ساعت می‌خوابم',
];
$f4_smoking = ['مصرف ندارم', 'سیگار یا قلیان کم', 'سیگار یا قلیان زیاد'];
$f4_alcohol = ['مصرف ندارم', 'کم', 'زیاد'];

/* ── گزینه‌های فرم ۵ ── */
$f5_services  = ['VIP', 'Premium', 'کلاس‌های گروهی / آکادمی', 'پیلاتس', 'آب‌درمانی', 'سایر'];
$f5_durations = ['کمتر از 1 ماه', '1 تا 3 ماه', '3 تا 6 ماه', '6 ماه تا 1 سال', '1 تا 3 سال', 'بیشتر از 3 سال'];
$f5_goals     = ['کاهش وزن و چربی', 'افزایش عضله و قدرت', 'سلامت عمومی و Longevity', 'کاهش درد و بهبود حرکت', 'اصلاح پاسچر', 'آمادگی جسمانی', 'عملکرد ورزشی', 'سایر'];
$f5_expect    = ['بسیار بهتر از انتظار', 'بهتر از انتظار', 'مطابق انتظار', 'کمتر از انتظار', 'بسیار کمتر از انتظار'];
$f5_scale5a   = ['بسیار زیاد', 'زیاد', 'متوسط', 'کم', 'اصلاً'];
$f5_know      = ['کاملاً', 'تا حدودی', 'خیر'];
$f5_scale5b   = ['کاملاً', 'زیاد', 'متوسط', 'کم', 'اصلاً'];
$f5_scale5c   = ['بسیار زیاد', 'زیاد', 'متوسط', 'کم', 'بسیار کم'];
$f5_knows     = ['کاملاً', 'تا حدودی', 'کم', 'خیر'];
$f5_watch     = ['بسیار خوب', 'خوب', 'مناسب', 'کمتر از انتظار', 'بسیار کمتر از انتظار', 'برای من اهمیت خاصی ندارد'];
$f5_crowd     = ['هرگز', 'به‌ندرت', 'گاهی', 'زیاد', 'تقریباً همیشه'];
$f5_value     = ['بسیار بالا', 'بالا', 'مناسب', 'پایین', 'بسیار پایین'];
$f5_quit      = ['قیمت', 'ساعت تمرین', 'رفت‌وآمد', 'شلوغی', 'کیفیت مربی', 'کمبود توجه شخصی', 'نتیجه نگرفتن', 'آسیب یا مشکلات جسمانی', 'مسائل شخصی یا کاری', 'سایر', 'در حال حاضر دلیلی برای قطع عضویت ندارم'];

$jMonths = [
    1 => 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
];

/* ── دیکشنری ترجمه ── */
$DICT = [
    'مجموعه فرم‌های آنلاین — پروژه درس برنامه‌سازی وب (PHP)' => 'Online Forms Suite — Web Programming Project (PHP)',
    'فرم‌های همکاری آنلاین' => 'Online Coaching Forms',
    'فعال' => 'Active', 'به‌زودی' => 'Coming soon', 'شروع فرم' => 'Start form',
    'لینک اختصاصی فرم' => 'Dedicated form link', 'پاسخ ثبت‌شده' => 'submissions',
    'پنل مربی' => 'Coach Panel', 'ورود مدیر' => 'Admin login', 'خروج' => 'Log out',
    'مربیگری آنلاین' => 'Online Coaching', 'جستجو بر اساس نام…' => 'Search by name…',
    'فرم شروع همکاری آنلاین' => 'Online Onboarding Form',
    'فرم مشاوره اولیه' => 'Initial Consultation Form',
    'فرم ارزیابی مسیر اعضای آنلاین' => 'Online Members Progress Review',
    'فرم مشاوره اولیه سازمانی' => 'Organizational Initial Consultation',
    'فرم نظرسنجی رضایت اعضای SAEID CLUB' => 'SAEID CLUB Member Satisfaction Survey',
    'اطلاعات فردی' => 'Personal Information',
    'اطلاعات تماس' => 'Contact Information',
    'سبک زندگی' => 'Lifestyle',
    'سابقه تمرینی' => 'Training History',
    'اهداف' => 'Goals',
    'وضعیت جسمانی' => 'Physical Condition',
    'تغذیه' => 'Nutrition',
    'امکانات تمرینی' => 'Training Equipment',
    'مدارک اولیه' => 'Initial Documents',
    'مشخصات عضویت' => 'Membership Info',
    'رضایت کلی از Saeid Club' => 'Overall Satisfaction',
    'نتیجه و پیشرفت' => 'Results & Progress',
    'برنامه و مربی' => 'Program & Coach',
    'نظارت و پشتیبانی Saeid Club' => 'Supervision & Support',
    'محیط، امکانات و تجربه حضور' => 'Facilities & Experience',
    'ارزش خدمات و احتمال ادامه عضویت' => 'Value & Continuation',
    'وفاداری، نقاط قوت و پیشنهادها' => 'Loyalty & Suggestions',
    'نام و نام خانوادگی' => 'Full name',
    'شماره تماس' => 'Phone number',
    'کشور محل زندگی' => 'Country of residence',
    'شغل' => 'Occupation',
    'قد (سانتی‌متر)' => 'Height (cm)', 'وزن (کیلوگرم)' => 'Weight (kg)',
    'قد (cm)' => 'Height (cm)', 'وزن (kg)' => 'Weight (kg)',
    'تاریخ تولد' => 'Date of birth', 'تاریخ تولد (شمسی)' => 'Date of birth (Jalali)',
    'تاریخ تولد (میلادی)' => 'Date of birth (Gregorian)', 'سن (محاسبه خودکار)' => 'Age (auto)',
    'روز' => 'Day', 'ماه' => 'Month', 'سال' => 'Year',
    'فروردین' => 'Farvardin', 'اردیبهشت' => 'Ordibehesht', 'خرداد' => 'Khordad',
    'تیر' => 'Tir', 'مرداد' => 'Mordad', 'شهریور' => 'Shahrivar',
    'مهر' => 'Mehr', 'آبان' => 'Aban', 'آذر' => 'Azar',
    'دی' => 'Dey', 'بهمن' => 'Bahman', 'اسفند' => 'Esfand',
    'پایان' => 'Finish', 'مشاهده پاسخ‌ها' => 'View answers',
    'دانلود JSON' => 'Download JSON', 'حذف پاسخ' => 'Delete submission',
    'تاریخ ثبت' => 'Submitted at', 'تعداد پاسخ' => 'Answers', 'عملیات' => 'Actions',
    'تولدها' => 'Birthdays', 'امروز تولد کسی نیست.' => 'No birthdays today.',
    'تبریک گفته شد' => 'Greeted', 'تولدهای نزدیک' => 'Upcoming birthdays',
    'مدیریت و افزودن' => 'Manage & add',
    'افزودن دستی تولد (تاریخ شمسی)' => 'Add birthday manually (Jalali date)',
    'لینک شما پیدا نشد' => 'Link not found',
    'ورود به پنل مربی' => 'Coach Panel Login',
    'نام کاربری' => 'Username', 'رمز عبور' => 'Password', 'ورود' => 'Log in',
];
