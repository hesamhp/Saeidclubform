<?php
/* ============================================================
 *  ۲. توابع کمکی (functions.php)
 *  شامل: امنیت XSS، لینک‌سازی، تبدیل تاریخ شمسی، ذخیره JSON، دفترچه تولدها
 * ============================================================ */

function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function formUrl($slug) {
    global $baseUrl, $lang;
    return $baseUrl . '?form=' . rawurlencode($slug) . '&lang=' . rawurlencode($lang);
}

function submissionsDir($slug) {
    global $projectDir;
    $dir = $projectDir . DIRECTORY_SEPARATOR . 'submissions' . DIRECTORY_SEPARATOR . $slug;
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
            appLog('error', 'Submission directory could not be created.', [
                'form' => $slug,
                'directory' => $dir,
            ]);
        }
    }
    return $dir;
}

function isChecked($field, $value) {
    return (($_POST[$field] ?? null) === $value) ? 'checked' : '';
}

function isCheckedArr($field, $value) {
    return in_array($value, $_POST[$field] ?? [], true) ? 'checked' : '';
}

function isSelectedDate($field, $value) {
    return ((string)($_POST[$field] ?? '') === (string)$value) ? ' selected' : '';
}

function haveRadios($name) {
    $opts = [
        'دارم'  => 'peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/30 peer-checked:font-bold',
        'ندارم' => 'peer-checked:bg-zinc-600   peer-checked:text-white peer-checked:ring-zinc-500   peer-checked:shadow-lg peer-checked:shadow-black/40',
    ];
    $first = true;
    foreach ($opts as $val => $cls) {
        echo '<label class="cursor-pointer">'
           . '<input type="radio" name="' . e($name) . '" value="' . e($val) . '" class="peer sr-only" ' . isChecked($name, $val) . ($first ? ' required' : '') . '>'
           . '<span class="inline-block text-sm rounded-xl px-5 py-2.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition hover:ring-yellow-400/60 ' . $cls . '">'
           . e($val)
           . '</span></label>';
        $first = false;
    }
}

function radioGroup($name, array $options, $cols = false) {
    $first = true;
    foreach ($options as $opt) {
        echo '<label class="cursor-pointer ' . ($cols ? 'block' : 'flex-1 min-w-[7rem]') . '">'
           . '<input type="radio" name="' . e($name) . '" value="' . e($opt) . '" class="peer sr-only" '
           . isChecked($name, $opt) . ($first ? ' required' : '') . '>'
           . '<span class="block text-center text-sm rounded-xl px-4 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition '
           . 'peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 '
           . 'peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60">'
           . e($opt) . '</span></label>';
        $first = false;
    }
}

function checkGroup($name, array $options) {
    foreach ($options as $opt) {
        echo '<label class="cursor-pointer">'
           . '<input type="checkbox" name="' . e($name) . '[]" value="' . e($opt) . '" class="peer sr-only" '
           . isCheckedArr($name, $opt) . '>'
           . '<span class="inline-flex items-center gap-2 text-sm rounded-full px-4 py-2.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition '
           . 'peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 '
           . 'peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60 '
           . '[&_svg]:opacity-0 peer-checked:[&_svg]:opacity-100 [&_svg]:transition-opacity">'
           . '<i data-lucide="check" class="w-3.5 h-3.5"></i>' . e($opt) . '</span></label>';
    }
}

function fullNameFileName($fullname) {
    $safe = trim(preg_replace('/\s+/u', ' ', (string)$fullname));
    $safe = preg_replace('/[\\\\\/:*?"<>|]/u', '', $safe);
    $safe = trim($safe, " .\t\n\r\0\x0B");
    return ($safe !== '' && $safe !== '.' && $safe !== '..') ? $safe : 'user_' . time();
}

function humanSize($bytes) {
    if ($bytes < 1024) return $bytes . ' بایت';
    return round($bytes / 1024, 1) . ' کیلوبایت';
}

function bmiLabel($bmi) {
    if ($bmi < 18.5) return 'کم‌وزن';
    if ($bmi < 25)   return 'نرمال';
    if ($bmi < 30)   return 'اضافه‌وزن';
    return 'چاق';
}

function secTxt($title) {
    return "\n■ " . $title . "\n" . "--------------------------------------------------\n";
}

function rowTxt($label, $value) {
    $value = trim((string)$value);
    return $label . ': ' . ($value !== '' ? $value : '---') . "\n";
}

/* ── تبدیل تاریخ شمسی ↔ میلادی ── */
const GREGORIAN_EPOCH = 1721425.5;
const JALALI_EPOCH    = 1948320.5;

function leapGregorian($gy) {
    return ($gy % 4 === 0) && !($gy % 100 === 0 && $gy % 400 !== 0);
}

function gregorianToFixed($gy, $gm, $gd) {
    $prior = $gy - 1;
    $fixed = GREGORIAN_EPOCH - 1 + 365 * $prior + intdiv($prior, 4) - intdiv($prior, 100) + intdiv($prior, 400) + intdiv(367 * $gm - 362, 12);
    if ($gm > 2) {
        $fixed += (leapGregorian($gy) ? -1 : -2);
    }
    return $fixed + $gd;
}

function jalaaliToFixed($jy, $jm, $jd) {
    $epbase = $jy - ($jy >= 0 ? 474 : 473);
    $cycle  = $epbase % 2820;
    if ($cycle < 0) $cycle += 2820;
    $epyear = 474 + $cycle;
    $mdays  = ($jm <= 7) ? (($jm - 1) * 31) : (($jm - 1) * 30 + 6);
    return $jd + $mdays + intdiv($epyear * 682 - 110, 2816) + ($epyear - 1) * 365 + intdiv($epbase, 2820) * 1029983 + (JALALI_EPOCH - 1);
}

function fixedToGregorian($jd) {
    $wjd    = ($jd - 0.5) + 0.5;
    $depoch = $wjd - GREGORIAN_EPOCH;
    $quadricent = floor($depoch / 146097);
    $dqc        = $depoch - $quadricent * 146097;
    $cent       = floor($dqc / 36524);
    $dcent      = $dqc - $cent * 36524;
    $quad       = floor($dcent / 1461);
    $dquad      = $dcent - $quad * 1461;
    $yindex     = floor($dquad / 365);
    $year = (int)($quadricent * 400 + $cent * 100 + $quad * 4 + $yindex);
    if (!($cent == 4 || $yindex == 4)) $year++;
    $yearday = $wjd - gregorianToFixed($year, 1, 1);
    $leapadj = ($wjd < gregorianToFixed($year, 3, 1)) ? 0 : (leapGregorian($year) ? 1 : 2);
    $month   = (int)floor((($yearday + $leapadj) * 12 + 373) / 365);
    $day     = (int)($wjd - gregorianToFixed($year, $month, 1)) + 1;
    return [$year, $month, $day];
}

function fixedToJalaali($jd) {
    $depoch = $jd - jalaaliToFixed(475, 1, 1);
    $cycle  = floor($depoch / 1029983);
    $cyear  = $depoch - $cycle * 1029983;
    if ($cyear == 1029982) {
        $ycycle = 2820;
    } else {
        $aux1   = floor($cyear / 366);
        $aux2   = $cyear - $aux1 * 366;
        $ycycle = floor((2134 * $aux1 + 2816 * $aux2 + 2815) / 1028522) + $aux1 + 1;
    }
    $year = (int)($ycycle + 2820 * $cycle + 474);
    if ($year <= 0) $year--;
    $yday  = $jd - jalaaliToFixed($year, 1, 1) + 1;
    $month = ($yday <= 186) ? (int)ceil($yday / 31) : (int)ceil(($yday - 6) / 30);
    $day   = (int)($jd - jalaaliToFixed($year, $month, 1)) + 1;
    return [$year, $month, $day];
}

function jalaaliToGregorian($j_y, $j_m, $j_d) {
    return fixedToGregorian(jalaaliToFixed($j_y, $j_m, $j_d));
}

function gregorianToJalaali($g_y, $g_m, $g_d) {
    return fixedToJalaali(gregorianToFixed($g_y, $g_m, $g_d));
}

$currentJalali = gregorianToJalaali((int)date('Y'), (int)date('n'), (int)date('j'));

function jalaliIsFuture($jy, $jm, $jd) {
    global $currentJalali;
    [$ty, $tm, $td] = $currentJalali;
    if ($jy !== $ty) return $jy > $ty;
    if ($jm !== $tm) return $jm > $tm;
    return $jd > $td;
}

function printJalaliDateInputs() {
    global $jMonths, $currentJalali;
    [$curJY] = $currentJalali;
    $cls = 'w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition';
    echo '<div class="grid grid-cols-3 gap-3">';
    echo '<div><label class="block text-xs text-zinc-500 mb-1.5">روز</label><select name="bd_day" class="' . $cls . '"><option value="">—</option>';
    for ($d = 1; $d <= 31; $d++) {
        echo '<option value="' . $d . '"' . isSelectedDate('bd_day', $d) . '>' . $d . '</option>';
    }
    echo '</select></div>';
    echo '<div><label class="block text-xs text-zinc-500 mb-1.5">ماه</label><select name="bd_month" class="' . $cls . '"><option value="">—</option>';
    foreach ($jMonths as $num => $name) {
        echo '<option value="' . $num . '"' . isSelectedDate('bd_month', $num) . '>' . $name . '</option>';
    }
    echo '</select></div>';
    echo '<div><label class="block text-xs text-zinc-500 mb-1.5">سال</label><select name="bd_year" class="' . $cls . '"><option value="">—</option>';
    for ($y = $curJY; $y >= $curJY - 90; $y--) {
        echo '<option value="' . $y . '"' . isSelectedDate('bd_year', $y) . '>' . $y . '</option>';
    }
    echo '</select></div></div>';
}

function calcAgeText($birthdate) {
    if ($birthdate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) return '';
    $dob = date_create($birthdate);
    if (!$dob || $dob > date_create('today')) return '';
    return $dob->diff(date_create('today'))->y . ' سال';
}

/* ── ذخیره و خواندن پاسخ‌های JSON ── */
function saveSubmission($slug, $fullname, array $answers) {
    global $forms;
    $dir  = submissionsDir($slug);
    $safe = fullNameFileName($fullname);
    $fileName = $safe . '.json';
    $counter  = 1;
    while (file_exists($dir . DIRECTORY_SEPARATOR . $fileName)) {
        $fileName = $safe . '_' . $counter . '.json';
        $counter++;
    }
    $clean = [];
    foreach ($answers as $label => $value) {
        if (is_array($value)) $value = implode('، ', $value);
        $value = trim((string)$value);
        $clean[$label] = ($value !== '') ? $value : '—';
    }
    $payload = [
        'form'         => $slug,
        'form_title'   => $forms[$slug]['title'] ?? $slug,
        'submitted_at' => date('Y-m-d H:i:s'),
        'full_name'    => $fullname,
        'answers'      => $clean,
    ];
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        appLog('error', 'Submission JSON encoding failed.', ['form' => $slug, 'json_error' => json_last_error_msg()]);
        return false;
    }
    $path = $dir . DIRECTORY_SEPARATOR . $fileName;
    $ok   = file_put_contents($path, $json, LOCK_EX);
    if ($ok === false) {
        appLog('error', 'Submission file could not be written.', ['form' => $slug, 'directory' => $dir]);
        return false;
    }
    return $fileName;
}

/* ── دفترچه تولدها ── */
function peopleFile() {
    global $projectDir;
    $dir = $projectDir . DIRECTORY_SEPARATOR . 'submissions';
    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        appLog('error', 'People directory could not be created.', ['directory' => $dir]);
    }
    return $dir . DIRECTORY_SEPARATOR . 'people.json';
}

function loadPeople() {
    $file = peopleFile();
    if (!is_file($file)) return [];
    $raw = file_get_contents($file);
    if ($raw === false) return [];
    $data = json_decode((string)$raw, true);
    return is_array($data) ? $data : [];
}

function storePeople(array $people) {
    $file = peopleFile();
    $json = json_encode(array_values($people), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) return false;
    return file_put_contents($file, $json, LOCK_EX) !== false;
}

function addPerson($name, $jy, $jm, $jd, $source = 'form') {
    $name = trim((string)$name);
    if ($name === '' || !$jy || !$jm || !$jd) return false;
    $people = loadPeople();
    foreach ($people as $p) {
        if (($p['name'] ?? '') === $name && (int)($p['bmonth'] ?? 0) === (int)$jm && (int)($p['bday'] ?? 0) === (int)$jd) {
            return false;
        }
    }
    $people[] = [
        'id'        => uniqid('p', true),
        'name'      => $name,
        'byear'     => (int)$jy,
        'bmonth'    => (int)$jm,
        'bday'      => (int)$jd,
        'source'    => $source,
        'added_at'  => date('Y-m-d H:i:s'),
        'done_year' => null,
    ];
    return storePeople($people);
}

function deletePerson($id) {
    $people = loadPeople();
    $people = array_filter($people, fn($p) => ($p['id'] ?? '') !== $id);
    return storePeople($people);
}

function markBirthdayDone($id, $jalaliYear) {
    $people = loadPeople();
    foreach ($people as &$p) {
        if (($p['id'] ?? '') === $id) {
            $p['done_year'] = (int)$jalaliYear;
            break;
        }
    }
    return storePeople($people);
}

function jalaliAge($jy, $jm, $jd) {
    global $currentJalali;
    [$ty, $tm, $td] = $currentJalali;
    $age = $ty - (int)$jy;
    if ($tm < $jm || ($tm === $jm && $td < $jd)) $age--;
    return max($age, 0);
}

function birthdayBoard() {
    global $currentJalali, $jMonths;
    [$ty, $tm, $td] = $currentJalali;
    $today = [];
    $upcoming = [];
    foreach (loadPeople() as $p) {
        $bm = (int)($p['bmonth'] ?? 0);
        $bd = (int)($p['bday'] ?? 0);
        if (!$bm || !$bd) continue;
        $p['age'] = jalaliAge($p['byear'] ?? 0, $bm, $bd);
        $p['date_text'] = $bd . ' ' . ($jMonths[$bm] ?? '');
        if ($bm === $tm && $bd === $td) {
            $p['done'] = ((int)($p['done_year'] ?? 0) === $ty);
            $today[] = $p;
        } else {
            $dayOfYear = fn($m, $d) => (($m <= 6) ? ($m - 1) * 31 : 186 + ($m - 7) * 30) + $d;
            $diff = $dayOfYear($bm, $bd) - $dayOfYear($tm, $td);
            if ($diff < 0) $diff += 365;
            if ($diff > 0 && $diff <= 7) {
                $p['days_left'] = $diff;
                $upcoming[] = $p;
            }
        }
    }
    usort($upcoming, fn($a, $b) => $a['days_left'] - $b['days_left']);
    return ['today' => $today, 'upcoming' => $upcoming];
}

/* ── چندزبانه و اعداد لاتین ── */
function translatePage($html) {
    global $DICT;
    $chunks = preg_split('/(<script\b[^>]*>.*?<\/script>|<style\b[^>]*>.*?<\/style>)/si', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    static $long = null;
    if ($long === null) {
        $long = array_filter($DICT, fn($k) => mb_strlen($k) >= 6, ARRAY_FILTER_USE_KEY);
    }
    foreach ($chunks as $i => $chunk) {
        if (stripos($chunk, '<script') === 0 || stripos($chunk, '<style') === 0) continue;
        $chunk = preg_replace_callback('/>([^<>]+)(?=<)/u', function ($m) use ($DICT, $long) {
            $raw = $m[1];
            $key = trim($raw);
            if ($key === '') return $m[0];
            if (isset($DICT[$key])) return '>' . str_replace($key, $DICT[$key], $raw);
            return '>' . strtr($raw, $long);
        }, $chunk);
        $chunk = preg_replace_callback('/(placeholder|title)="([^"]*)"/u', function ($m) use ($DICT) {
            return $m[1] . '="' . ($DICT[$m[2]] ?? $m[2]) . '"';
        }, $chunk);
        $chunks[$i] = $chunk;
    }
    return implode('', $chunks);
}

function localizeDigits($html) {
    return strtr($html, [
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
        '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
        '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
    ]);
}
