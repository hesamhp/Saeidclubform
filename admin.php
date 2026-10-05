<?php
/* ============================================================
 *  ۴. قالب پنل مربی (admin.php)
 *  شامل: صفحه ورود، تابلوی تولدها با دکمه Done، جستجو و جدول نتایج
 * ============================================================ */
?>
<?php if ($isPanel && !$isAdmin): ?>
    <!-- ══════════ صفحه ورود مدیر ══════════ -->
    <section class="rise rise-2 min-h-[70vh] grid place-items-center">
        <div class="w-full max-w-sm mx-auto">
            <div class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/60 ring-1 ring-zinc-800 p-8">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/30">
                    <i data-lucide="lock-keyhole" class="w-8 h-8"></i>
                </div>
                <h1 class="mt-6 text-xl font-extrabold text-zinc-100 text-center">ورود به پنل مربی</h1>
                <p class="mt-2 text-xs text-zinc-500 text-center leading-6">این بخش فقط برای مربی است؛ لطفاً وارد شوید.</p>

                <?php if ($loginError): ?>
                <div class="shake mt-5 rounded-xl bg-rose-500/10 border border-rose-500/30 p-3 text-sm text-rose-300 flex items-center gap-2">
                    <i data-lucide="circle-alert" class="w-4 h-4 shrink-0"></i>
                    نام کاربری یا رمز عبور اشتباه است.
                </div>
                <?php endif; ?>

                <form method="post" action="<?= e($panelUrl) ?>" class="mt-6 space-y-4">
                    <div>
                        <label for="login_user" class="block text-sm font-medium text-zinc-300 mb-2">نام کاربری</label>
                        <input type="text" id="login_user" name="login_user" required dir="ltr" autocomplete="username"
                               class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition text-left">
                    </div>
                    <div>
                        <label for="login_pass" class="block text-sm font-medium text-zinc-300 mb-2">رمز عبور</label>
                        <input type="password" id="login_pass" name="login_pass" required dir="ltr" autocomplete="current-password"
                               class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition text-left">
                    </div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-l from-yellow-300 to-amber-500 text-black font-bold px-6 py-3.5 shadow-lg shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 transition">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        ورود
                    </button>
                </form>
            </div>
        </div>
    </section>

<?php elseif ($isPanel): ?>
    <!-- ══════════ پنل مدیریت مربی ══════════ -->
    <header class="rise rise-1 mb-10">
        <span class="inline-flex items-center gap-2 text-xs font-bold text-yellow-300 bg-yellow-400/10 border border-yellow-400/30 rounded-full px-4 py-1.5">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            دسترسی ویژه مربی
        </span>
        <h1 class="mt-4 text-3xl sm:text-5xl font-extrabold leading-tight bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 bg-clip-text text-transparent">
            پنل پاسخ‌های ثبت‌شده
        </h1>
        <p class="mt-3 text-zinc-500 text-sm leading-7">
            پاسخ‌های هر فرم در پوشه جداگانه خودش ذخیره شده‌اند.
        </p>
    </header>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="rise rise-2 mb-6 rounded-2xl p-4 flex items-center gap-3 text-sm font-medium
                <?= $_GET['deleted'] === '1' ? 'bg-yellow-400/10 border border-yellow-400/30 text-yellow-300' : 'bg-rose-500/10 border border-rose-500/30 text-rose-300' ?>">
        <i data-lucide="<?= $_GET['deleted'] === '1' ? 'circle-check' : 'circle-alert' ?>" class="w-5 h-5"></i>
        <?= $_GET['deleted'] === '1' ? 'پاسخ با موفقیت حذف شد.' : 'حذف انجام نشد؛ فایل پیدا نشد.' ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['bd'])): ?>
    <?php
    $bdMsgs = [
        'done'    => ['circle-check', 'تبریک تولد ثبت شد.', true],
        'added'   => ['circle-check', 'فرد جدید به دفترچه تولد اضافه شد.', true],
        'deleted' => ['circle-check', 'رکورد تولد حذف شد.', true],
        'invalid' => ['circle-alert', 'اطلاعات واردشده معتبر نیست.', false],
    ];
    $bdM = $bdMsgs[$_GET['bd']] ?? null;
    ?>
    <?php if ($bdM): ?>
    <div class="rise rise-2 mb-6 rounded-2xl p-4 flex items-center gap-3 text-sm font-medium
                <?= $bdM[2] ? 'bg-yellow-400/10 border border-yellow-400/30 text-yellow-300' : 'bg-rose-500/10 border border-rose-500/30 text-rose-300' ?>">
        <i data-lucide="<?= $bdM[0] ?>" class="w-5 h-5"></i>
        <?= e($bdM[1]) ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <!-- ══════════ تابلوی تولدها ══════════ -->
    <section class="rise rise-2 mb-8 rounded-3xl bg-zinc-900/80 backdrop-blur-xl ring-1 ring-zinc-800 shadow-2xl shadow-black/60 p-5 sm:p-7">
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-400 to-rose-500 text-white grid place-items-center shadow-lg shadow-rose-500/25">
                <i data-lucide="cake" class="w-5 h-5"></i>
            </span>
            <div>
                <h2 class="font-bold text-lg text-zinc-100">تولدها</h2>
                <p class="text-xs text-zinc-500">امروز: <?= $currentJalali[0] ?>/<?= sprintf('%02d', $currentJalali[1]) ?>/<?= sprintf('%02d', $currentJalali[2]) ?> — <?= e($jMonths[$currentJalali[1]]) ?></p>
            </div>
            <button type="button" onclick="document.getElementById('bdManage').classList.toggle('hidden')"
                    class="ms-auto inline-flex items-center gap-2 rounded-xl bg-zinc-800 px-4 py-2.5 text-xs font-bold text-zinc-200 ring-1 ring-zinc-700 hover:ring-yellow-400/50 hover:text-yellow-300 transition">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                مدیریت و افزودن
            </button>
        </div>

        <?php if (!empty($bdBoard['today'])): ?>
            <?php foreach ($bdBoard['today'] as $p): ?>
            <div class="mb-3 rounded-2xl p-5 ring-1 <?= $p['done'] ? 'bg-zinc-800/40 ring-zinc-700 opacity-60' : 'bg-gradient-to-l from-rose-500/15 to-yellow-400/10 ring-yellow-400/40' ?>">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <div class="min-w-0">
                        <p class="font-extrabold text-zinc-100 <?= $p['done'] ? 'line-through' : '' ?>">
                            امروز تولد <?= e($p['name']) ?> است!
                        </p>
                        <p class="text-xs text-zinc-400 mt-1">
                            <?= e($p['date_text']) ?> · <?= (int)$p['age'] ?> ساله شد
                            <span class="text-zinc-600">·</span> <?= e($p['source'] ?? '') ?>
                        </p>
                    </div>
                    <?php if (!$p['done']): ?>
                    <form method="post" action="<?= e($panelUrl) ?>" class="ms-auto">
                        <input type="hidden" name="bd_action" value="done">
                        <input type="hidden" name="person_id" value="<?= e($p['id']) ?>">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-xs font-extrabold text-black shadow-lg shadow-emerald-500/25 hover:bg-emerald-400 hover:-translate-y-px transition">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Done
                        </button>
                    </form>
                    <?php else: ?>
                    <span class="ms-auto inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400">
                        <i data-lucide="circle-check" class="w-4 h-4"></i>
                        تبریک گفته شد
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-zinc-500 flex items-center gap-2 py-2">
                <i data-lucide="calendar-off" class="w-4 h-4"></i>
                امروز تولد کسی نیست.
            </p>
        <?php endif; ?>

        <?php if (!empty($bdBoard['upcoming'])): ?>
        <div class="mt-4 pt-4 border-t border-zinc-800">
            <p class="text-xs font-bold text-zinc-400 mb-3 flex items-center gap-1.5">
                <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-yellow-400"></i>
                تولدهای نزدیک
            </p>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($bdBoard['upcoming'] as $p): ?>
                <span class="inline-flex items-center gap-2 rounded-full bg-zinc-800/70 ring-1 ring-zinc-700 px-3.5 py-2 text-xs text-zinc-300">
                    <i data-lucide="gift" class="w-3.5 h-3.5 text-rose-400"></i>
                    <?= e($p['name']) ?>
                    <span class="text-zinc-500"><?= e($p['date_text']) ?></span>
                    <span class="rounded-full bg-yellow-400/15 text-yellow-300 px-2 py-0.5 font-bold"><?= (int)$p['days_left'] ?> روز</span>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- پنل بازشونده مدیریت تولد -->
        <div id="bdManage" class="hidden mt-5 pt-5 border-t border-zinc-800 space-y-5">
            <form method="post" action="<?= e($panelUrl) ?>" class="rounded-2xl bg-zinc-950/60 ring-1 ring-zinc-800 p-4">
                <input type="hidden" name="bd_action" value="add">
                <p class="text-xs font-bold text-zinc-300 mb-3 flex items-center gap-1.5">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 text-yellow-400"></i>
                    افزودن دستی تولد (تاریخ شمسی)
                </p>
                <div class="grid sm:grid-cols-5 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs text-zinc-500 mb-1.5">نام و نام خانوادگی</label>
                        <input type="text" name="p_name" required placeholder="مثلاً: علی رضایی"
                               class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-2.5 text-sm text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1.5">روز</label>
                        <select name="p_day" required class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-3 py-2.5 text-sm text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                            <option value="">—</option>
                            <?php for ($d = 1; $d <= 31; $d++): ?><option value="<?= $d ?>"><?= $d ?></option><?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1.5">ماه</label>
                        <select name="p_month" required class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-3 py-2.5 text-sm text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                            <option value="">—</option>
                            <?php foreach ($jMonths as $mn => $mname): ?><option value="<?= $mn ?>"><?= e($mname) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1.5">سال</label>
                        <select name="p_year" required class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-3 py-2.5 text-sm text-zinc-100 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                            <option value="">—</option>
                            <?php for ($y = $currentJalali[0]; $y >= $currentJalali[0] - 90; $y--): ?><option value="<?= $y ?>"><?= $y ?></option><?php endfor; ?>
                        </select>
                    </div>
                </div>
                <button type="submit"
                        class="mt-3 inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-5 py-2.5 text-xs font-extrabold text-black shadow-md shadow-yellow-500/25 hover:bg-yellow-300 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    افزودن
                </button>
            </form>

            <?php if (!empty($allPeople)): ?>
            <div class="overflow-x-auto rounded-2xl ring-1 ring-zinc-800">
                <table class="w-full text-sm text-right">
                    <thead class="bg-zinc-950/80 text-zinc-400 text-xs">
                        <tr>
                            <th class="px-4 py-3 font-medium">نام</th>
                            <th class="px-4 py-3 font-medium">تاریخ تولد</th>
                            <th class="px-4 py-3 font-medium hidden sm:table-cell">سن</th>
                            <th class="px-4 py-3 font-medium hidden md:table-cell">منبع</th>
                            <th class="px-4 py-3 font-medium text-center">حذف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <?php foreach ($allPeople as $p): ?>
                        <tr class="hover:bg-zinc-800/40 transition">
                            <td class="px-4 py-3 font-medium text-zinc-100"><?= e($p['name'] ?? '') ?></td>
                            <td class="px-4 py-3 text-zinc-300"><?= (int)($p['bday'] ?? 0) ?> <?= e($jMonths[(int)($p['bmonth'] ?? 1)] ?? '') ?> <?= (int)($p['byear'] ?? 0) ?></td>
                            <td class="px-4 py-3 text-zinc-400 hidden sm:table-cell"><?= jalaliAge($p['byear'] ?? 0, $p['bmonth'] ?? 0, $p['bday'] ?? 0) ?> سال</td>
                            <td class="px-4 py-3 text-zinc-500 text-xs hidden md:table-cell"><?= e($p['source'] ?? '') ?></td>
                            <td class="px-4 py-3">
                                <form method="post" action="<?= e($panelUrl) ?>" class="flex justify-center"
                                      onsubmit="return confirm('این رکورد تولد حذف شود؟')">
                                    <input type="hidden" name="bd_action" value="delete">
                                    <input type="hidden" name="person_id" value="<?= e($p['id'] ?? '') ?>">
                                    <button type="submit" title="حذف"
                                            class="w-8 h-8 grid place-items-center rounded-lg bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20 hover:bg-rose-500 hover:text-white transition">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-xs text-zinc-500">هنوز کسی در دفترچه تولد ثبت نشده است.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- جستجو در جدول پاسخ‌ها -->
    <div class="rise rise-2 relative mb-5">
        <i data-lucide="search" class="w-4 h-4 text-zinc-500 absolute top-1/2 -translate-y-1/2 right-4 pointer-events-none"></i>
        <input id="panelSearch" type="text" placeholder="جستجو بر اساس نام…"
               class="w-full rounded-2xl border-0 ring-1 ring-zinc-700 bg-zinc-900/80 pr-11 pl-4 py-3.5 text-sm text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
    </div>

    <!-- تب‌های سریع فرم‌ها -->
    <div class="rise rise-2 mb-8 flex flex-wrap gap-2">
        <?php foreach ($panelData as $fs => $pd): ?>
        <a href="#section-<?= e($fs) ?>"
           class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-bold ring-1 ring-zinc-700 bg-zinc-900/80 text-zinc-300 hover:ring-yellow-400/60 hover:text-yellow-300 transition">
            <i data-lucide="<?= e($pd['meta']['icon']) ?>" class="w-3.5 h-3.5 text-yellow-400"></i>
            <?= e($pd['meta']['title']) ?>
            <span class="fa-num rounded-full bg-yellow-400/15 text-yellow-300 px-2 py-0.5"><?= count($pd['files']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <?php
    $grandCount = 0; $grandSize = 0; $grandLast = null;
    foreach ($panelData as $pd) {
        $grandCount += count($pd['files']);
        $grandSize  += $pd['totalSize'];
        if ($pd['last']) $grandLast = max($grandLast, $pd['last']);
    }
    ?>
    <section class="rise rise-2 grid grid-cols-3 gap-3 sm:gap-4 mb-8">
        <div class="rounded-2xl bg-zinc-900/80 ring-1 ring-zinc-800 p-4 sm:p-5 text-center hover:ring-yellow-400/40 transition">
            <i data-lucide="files" class="w-5 h-5 text-yellow-400 mx-auto mb-2"></i>
            <div class="text-2xl sm:text-3xl font-extrabold text-yellow-300 fa-num"><?= $grandCount ?></div>
            <div class="text-[11px] sm:text-xs text-zinc-500 mt-1">کل فایل‌های ثبت‌شده</div>
        </div>
        <div class="rounded-2xl bg-zinc-900/80 ring-1 ring-zinc-800 p-4 sm:p-5 text-center hover:ring-yellow-400/40 transition">
            <i data-lucide="hard-drive" class="w-5 h-5 text-yellow-400 mx-auto mb-2"></i>
            <div class="text-2xl sm:text-3xl font-extrabold text-yellow-300 fa-num"><?= humanSize($grandSize) ?></div>
            <div class="text-[11px] sm:text-xs text-zinc-500 mt-1">مجموع حجم</div>
        </div>
        <div class="rounded-2xl bg-zinc-900/80 ring-1 ring-zinc-800 p-4 sm:p-5 text-center hover:ring-yellow-400/40 transition">
            <i data-lucide="clock" class="w-5 h-5 text-yellow-400 mx-auto mb-2"></i>
            <div class="text-lg sm:text-xl font-extrabold text-yellow-300 fa-num"><?= $grandLast ? date('H:i', $grandLast) : '—' ?></div>
            <div class="text-[11px] sm:text-xs text-zinc-500 mt-1"><?= $grandLast ? 'آخرین ثبت — ' . date('Y/m/d', $grandLast) : 'آخرین ثبت' ?></div>
        </div>
    </section>

    <!-- جداول نتایج برای هر فرم -->
    <?php foreach ($panelData as $fs => $pd): ?>
    <section id="section-<?= e($fs) ?>" class="rise rise-3 scroll-mt-24 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/60 ring-1 ring-zinc-800 p-5 sm:p-8 mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25">
                <i data-lucide="<?= e($pd['meta']['icon']) ?>" class="w-5 h-5"></i>
            </span>
            <div>
                <h2 class="font-bold text-lg text-zinc-100"><?= e($pd['meta']['title']) ?></h2>
                <p dir="ltr" class="text-[11px] font-mono text-zinc-600"><?= e(formUrl($fs)) ?></p>
            </div>
            <span class="ms-auto text-xs font-bold bg-yellow-400/10 text-yellow-300 border border-yellow-400/25 rounded-full px-3 py-1 fa-num">
                <?= count($pd['files']) ?> پاسخ · <?= humanSize($pd['totalSize']) ?>
            </span>
        </div>

        <?php if (empty($pd['files'])): ?>
            <p class="text-sm text-zinc-500 flex items-center gap-2 py-3">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                برای این فرم هنوز پاسخی ثبت نشده است.
            </p>
        <?php else: ?>
            <div class="overflow-x-auto rounded-2xl ring-1 ring-zinc-800">
                <table class="w-full text-sm text-right">
                    <thead class="bg-zinc-950/80 text-zinc-400 text-xs">
                        <tr>
                            <th class="px-4 py-3 font-medium">#</th>
                            <th class="px-4 py-3 font-medium">نام و نام خانوادگی</th>
                            <th class="px-4 py-3 font-medium hidden sm:table-cell">تاریخ ثبت</th>
                            <th class="px-4 py-3 font-medium hidden md:table-cell">تعداد پاسخ</th>
                            <th class="px-4 py-3 font-medium text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <?php foreach ($pd['files'] as $i => $f): ?>
                        <tr class="hover:bg-zinc-800/40 transition" data-row="sub" data-name="<?= e($f['person']) ?>">
                            <td class="px-4 py-3 text-zinc-500 fa-num"><?= $i + 1 ?></td>
                            <td class="px-4 py-3 font-medium text-zinc-100"><?= e($f['person']) ?></td>
                            <td class="px-4 py-3 text-zinc-400 fa-num hidden sm:table-cell" dir="ltr"><?= e($f['date']) ?></td>
                            <td class="px-4 py-3 text-zinc-400 fa-num hidden md:table-cell"><?= count($f['answers']) ?> مورد</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" title="مشاهده پاسخ‌ها"
                                            onclick="document.getElementById('detail-<?= e($fs) ?>-<?= $i ?>').classList.toggle('hidden')"
                                            class="w-9 h-9 grid place-items-center rounded-lg bg-zinc-800 text-zinc-300 ring-1 ring-zinc-700 hover:bg-zinc-700 hover:text-yellow-300 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <a href="<?= e($baseUrl . '?download=' . rawurlencode($fs . '/' . $f['name'])) ?>" title="دانلود JSON"
                                       class="w-9 h-9 grid place-items-center rounded-lg bg-yellow-400/10 text-yellow-400 ring-1 ring-yellow-400/25 hover:bg-yellow-400 hover:text-black transition">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                    <form method="post" action="<?= e($panelUrl) ?>" onsubmit="return confirm('مطمئنی این پاسخ برای همیشه حذف شود؟')">
                                        <input type="hidden" name="delete_form" value="<?= e($fs) ?>">
                                        <input type="hidden" name="delete_file" value="<?= e($f['name']) ?>">
                                        <button type="submit" title="حذف پاسخ"
                                                class="w-9 h-9 grid place-items-center rounded-lg bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20 hover:bg-rose-500 hover:text-white transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="detail-<?= e($fs) ?>-<?= $i ?>" class="detail-row hidden bg-zinc-950/60">
                            <td colspan="5" class="px-4 py-4">
                                <p dir="ltr" class="text-[11px] font-mono text-zinc-600 mb-3"><?= e($f['name']) ?> · <?= humanSize($f['size']) ?></p>
                                <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2">
                                    <?php foreach ($f['answers'] as $question => $answer): ?>
                                    <div class="flex gap-2 text-xs leading-6 border-b border-zinc-800/60 py-1.5">
                                        <span class="text-zinc-500 shrink-0"><?= e($question) ?>:</span>
                                        <span class="text-zinc-200 font-medium"><?= e($answer) ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
    <?php endforeach; ?>

<?php endif; ?>
