<?php
/* ============================================================
 *  ۵. قالب‌های فرم‌ها (forms.php)
 *  شامل: صفحات فرم‌های ۱ تا ۵ و صفحه پایان بعد از ثبت
 * ============================================================ */
?>
<?php if ($slug !== null && !$isActiveForm): ?>
    <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/60 ring-1 ring-zinc-800 p-10 sm:p-14 text-center mt-10">
        <div class="mx-auto w-20 h-20 rounded-2xl bg-zinc-800 text-zinc-500 grid place-items-center ring-1 ring-zinc-700">
            <i data-lucide="lock" class="w-9 h-9"></i>
        </div>
        <h2 class="mt-6 text-2xl font-bold text-zinc-200">این فرم هنوز فعال نشده است</h2>
        <p class="mt-3 text-zinc-500 leading-7">
            «<?= e($forms[$slug]['title']) ?>» به‌زودی اضافه خواهد شد؛
            لطفاً بعداً دوباره همین لینک را باز کنید.
        </p>
    </section>

<?php elseif ($slug !== null): ?>
    <header class="text-center mb-10 rise rise-1">
        <span class="inline-flex items-center gap-2 text-xs font-bold text-yellow-300 bg-yellow-400/10 border border-yellow-400/30 rounded-full px-4 py-1.5">
            <i data-lucide="dumbbell" class="w-4 h-4"></i>
            فرم <?= e($slug) ?> — <?= $slug === 'form5' ? 'همه موارد اختیاری' : 'همه موارد اجباری هستند' ?>
        </span>
        <h1 class="mt-5 text-3xl sm:text-5xl font-extrabold leading-tight bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 bg-clip-text text-transparent">
            <?= e($forms[$slug]['title']) ?>
        </h1>
        <p class="mt-4 text-zinc-400 leading-7 max-w-xl mx-auto text-sm sm:text-base">
            <?php if ($slug === 'form5'): ?>
            سلام! نظر شما برای ما ارزشمند است — پر کردن هیچ سؤالی اجباری نیست و می‌توانید فقط به سؤال‌هایی که دوست دارید پاسخ دهید.
            <?php else: ?>
            سلام! برای شروع مسیر، لطفاً این فرم را با دقت و <span class="text-yellow-300 font-bold">کامل</span> پر کنید — همه موارد ستاره‌دار (*) اجباری هستند.
            <?php endif; ?>
        </p>
    </header>

    <?php if ($success): ?>
    <!-- ══════════ صفحه پایان بعد از ثبت موفق ══════════ -->
    <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/60 ring-1 ring-zinc-800 p-8 sm:p-12 text-center">
        <div class="pulse-ring mx-auto w-20 h-20 rounded-2xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/30">
            <i data-lucide="circle-check" class="w-10 h-10"></i>
        </div>
        <h2 class="mt-6 text-2xl font-bold text-zinc-100">فرم شما با موفقیت ثبت شد!</h2>
        <p class="mt-3 text-zinc-400 leading-7">از وقتی که گذاشتید سپاسگزاریم؛ پاسخ‌های شما برای مربی ارسال شد.</p>

        <?php if ($slug === 'form2' || $slug === 'form4'): ?>
        <div class="mt-7 rounded-2xl bg-gradient-to-l from-zinc-950 to-zinc-900 ring-1 ring-yellow-400/25 p-6">
            <p class="text-zinc-200 font-bold flex items-center justify-center gap-2">
                <i data-lucide="phone-incoming" class="w-5 h-5 text-yellow-400"></i>
                منتظر تماس همکاران ما باشید
            </p>
            <p class="mt-3 text-sm text-zinc-400">ارادتمند، تیم سعید کلاب</p>
            <a href="tel:+989225830811" dir="ltr"
               class="mt-3 inline-flex items-center gap-2 font-mono font-bold text-yellow-300 hover:text-yellow-200 transition fa-num">
                <i data-lucide="phone" class="w-4 h-4"></i>
                0922-583-0811
            </a>
        </div>
        <?php endif; ?>

        <div class="mt-8 flex items-center justify-center">
            <a href="<?= e(formUrl($slug)) ?>"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-l from-yellow-300 to-amber-500 text-black font-bold px-10 py-3.5 shadow-lg shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 transition">
                <i data-lucide="check" class="w-4 h-4"></i>
                پایان
            </a>
        </div>
    </section>

    <?php else: ?>

    <?php if (!empty($errors)): ?>
    <div class="rise rise-2 mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/30 p-5">
        <div class="flex items-center gap-2 text-rose-300 font-bold mb-2">
            <i data-lucide="circle-alert" class="w-5 h-5"></i>
            لطفاً موارد زیر را اصلاح کنید:
        </div>
        <ul class="list-disc pr-6 text-rose-300/80 text-sm space-y-1 leading-7">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($slug === 'form1'): ?>
    <!-- ══════════ فرم ۱ ══════════ -->
    <form method="post" action="<?= e(formUrl($slug)) ?>" id="surveyForm" class="space-y-6">
        <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="user" class="w-5 h-5"></i></span>
                <div><h2 class="font-bold text-lg text-zinc-100">اطلاعات فردی</h2><p class="text-xs text-zinc-500">بخش ۱ از ۸</p></div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="fullname" class="block text-sm font-medium text-zinc-300 mb-2">نام و نام خانوادگی <span class="text-yellow-400">*</span></label>
                    <input type="text" id="fullname" name="fullname" required value="<?= e($_POST['fullname'] ?? '') ?>" placeholder="مثلاً: علی رضایی" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">تاریخ تولد (شمسی) <span class="text-yellow-400">*</span></label>
                    <?php printJalaliDateInputs(); ?>
                    <p class="mt-2 text-xs text-zinc-500 flex items-center gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i>سن شما به‌صورت خودکار محاسبه می‌شود.</p>
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-zinc-300 mb-2">کشور محل زندگی <span class="text-yellow-400">*</span></label>
                    <input type="text" id="country" name="country" placeholder="مثلاً: ایران" required value="<?= e($_POST['country'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                </div>
                <div class="sm:col-span-2">
                    <label for="job" class="block text-sm font-medium text-zinc-300 mb-2">شغل <span class="text-yellow-400">*</span></label>
                    <input type="text" id="job" name="job" placeholder="مثلاً: برنامه‌نویس" required value="<?= e($_POST['job'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                </div>
                <div>
                    <label for="height" class="block text-sm font-medium text-zinc-300 mb-2">قد (سانتی‌متر) <span class="text-yellow-400">*</span></label>
                    <input type="number" id="height" name="height" min="100" max="230" placeholder="مثلاً: ۱۷۸" required value="<?= e($_POST['height'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                </div>
                <div>
                    <label for="weight" class="block text-sm font-medium text-zinc-300 mb-2">وزن (کیلوگرم) <span class="text-yellow-400">*</span></label>
                    <input type="number" id="weight" name="weight" min="30" max="250" step="0.5" placeholder="مثلاً: ۸۲" required value="<?= e($_POST['weight'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
                </div>
            </div>
        </section>

        <section class="rise rise-3 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="briefcase" class="w-5 h-5"></i></span>
                <div><h2 class="font-bold text-lg text-zinc-100">سبک زندگی</h2><p class="text-xs text-zinc-500">بخش ۲ از ۸</p></div>
            </div>
            <div class="space-y-6">
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">شغل شما بیشتر: <span class="text-yellow-400">*</span></legend>
                    <div class="flex flex-wrap gap-2.5"><?php foreach ($jobActivities as $ja): ?><label class="cursor-pointer flex-1 min-w-[8rem]"><input type="radio" name="jobActivity" value="<?= e($ja) ?>" class="peer sr-only" <?= isChecked('jobActivity', $ja) ?> <?= $ja === $jobActivities[0] ? 'required' : '' ?>><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 peer-checked:-translate-y-0.5 hover:ring-yellow-400/60"><?= e($ja) ?></span></label><?php endforeach; ?></div>
                </fieldset>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label for="sitHours" class="block text-sm font-medium text-zinc-300 mb-2">میانگین ساعت نشستن روزانه <span class="text-yellow-400">*</span></label><input type="number" id="sitHours" name="sitHours" min="0" max="18" placeholder="مثلاً: ۸" required value="<?= e($_POST['sitHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="sleepHours" class="block text-sm font-medium text-zinc-300 mb-2">میانگین خواب شبانه (ساعت) <span class="text-yellow-400">*</span></label><input type="number" id="sleepHours" name="sleepHours" min="0" max="14" step="0.5" placeholder="مثلاً: ۷" required value="<?= e($_POST['sleepHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">سطح استرس: <span class="text-yellow-400">*</span></legend>
                    <div class="flex flex-wrap gap-2.5"><?php foreach ($stressOptions as $s): ?><label class="cursor-pointer flex-1 min-w-[6rem]"><input type="radio" name="stress" value="<?= e($s['label']) ?>" class="peer sr-only" <?= isChecked('stress', $s['label']) ?> <?= $s['label'] === 'کم' ? 'required' : '' ?>><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:text-white peer-checked:font-bold peer-checked:shadow-lg peer-checked:-translate-y-0.5 <?= $s['cls'] ?>"><?= e($s['label']) ?></span></label><?php endforeach; ?></div>
                </fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="history" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">سابقه تمرینی</h2><p class="text-xs text-zinc-500">بخش ۳ از ۸</p></div></div>
            <div class="space-y-5">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label for="sportHistory" class="block text-sm font-medium text-zinc-300 mb-2">سابقه ورزش <span class="text-yellow-400">*</span></label><input type="text" id="sportHistory" name="sportHistory" placeholder="مثلاً: ۳ سال بدنسازی" required value="<?= e($_POST['sportHistory'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="sportTypes" class="block text-sm font-medium text-zinc-300 mb-2">نوع ورزش‌های قبلی <span class="text-yellow-400">*</span></label><input type="text" id="sportTypes" name="sportTypes" placeholder="مثلاً: فوتبال، شنا (اگر ورزش نکردید بنویسید: هیچ‌کدام)" required value="<?= e($_POST['sportTypes'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <div><label for="trainDays" class="block text-sm font-medium text-zinc-300 mb-2">در حال حاضر چند روز در هفته تمرین می‌کنید؟ <span class="text-yellow-400">*</span></label><input type="number" id="trainDays" name="trainDays" min="0" max="7" placeholder="۰ تا ۷" required value="<?= e($_POST['trainDays'] ?? '') ?>" class="w-full sm:w-48 rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">محل تمرین: <span class="text-yellow-400">*</span></legend>
                    <div class="flex flex-wrap gap-2.5"><?php foreach ($trainPlaces as $tp): ?><label class="cursor-pointer"><input type="checkbox" name="trainPlace[]" value="<?= e($tp) ?>" class="peer sr-only" <?= isCheckedArr('trainPlace', $tp) ?>><span class="inline-flex items-center gap-2 text-sm rounded-full px-4 py-2.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60 [&_svg]:opacity-0 peer-checked:[&_svg]:opacity-100 [&_svg]:transition-opacity"><i data-lucide="check" class="w-3.5 h-3.5"></i><?= e($tp) ?></span></label><?php endforeach; ?></div>
                </fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="target" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">اهداف</h2><p class="text-xs text-zinc-500">بخش ۴ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">هدف اصلی شما از شروع این مسیر چیست؟ <span class="text-yellow-400">*</span></legend>
                    <div class="flex flex-wrap gap-2.5"><?php foreach ($goalsList as $g): ?><label class="cursor-pointer"><input type="checkbox" name="goals[]" value="<?= e($g) ?>" class="peer sr-only" <?= isCheckedArr('goals', $g) ?>><span class="inline-flex items-center gap-2 text-sm rounded-full px-4 py-2.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-gradient-to-l peer-checked:from-yellow-300 peer-checked:to-amber-500 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60 [&_svg]:opacity-0 peer-checked:[&_svg]:opacity-100 [&_svg]:transition-opacity"><i data-lucide="check" class="w-3.5 h-3.5"></i><?= e($g) ?></span></label><?php endforeach; ?></div>
                </fieldset>
                <div>
                    <label for="threeMonthGoal" class="block text-sm font-medium text-zinc-300 mb-2">هدف سه ماه آینده: <span class="text-yellow-400">*</span></label>
                    <textarea id="threeMonthGoal" name="threeMonthGoal" rows="4" maxlength="600" required placeholder="مثلاً: رسیدن به وزن ۷۵ کیلو با حفظ عضله…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['threeMonthGoal'] ?? '') ?></textarea>
                    <div class="mt-1.5 text-left text-xs text-zinc-500 fa-num"><span id="charCount">0</span> / 600</div>
                </div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="heart-pulse" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">وضعیت جسمانی</h2><p class="text-xs text-zinc-500">بخش ۵ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">آیا در حال حاضر درد یا محدودیت حرکتی دارید؟ <span class="text-yellow-400">*</span></legend>
                    <div class="flex gap-2.5">
                        <label class="cursor-pointer flex-1 max-w-[10rem]"><input type="radio" name="hasPain" value="بله" class="peer sr-only" <?= isChecked('hasPain', 'بله') ?> required><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-rose-500 peer-checked:text-white peer-checked:font-bold peer-checked:ring-rose-500 peer-checked:shadow-lg peer-checked:shadow-rose-500/25 hover:ring-rose-400/60">بله</span></label>
                        <label class="cursor-pointer flex-1 max-w-[10rem]"><input type="radio" name="hasPain" value="خیر" class="peer sr-only" <?= isChecked('hasPain', 'خیر') ?>><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60">خیر</span></label>
                    </div>
                </fieldset>
                <div id="painDetails" class="<?= $showPainBox ? '' : 'hidden' ?> space-y-5 rounded-2xl bg-rose-500/5 ring-1 ring-rose-500/20 p-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div><label for="painLocation" class="block text-sm font-medium text-zinc-300 mb-2">محل درد <span class="text-yellow-400">*</span></label><input type="text" id="painLocation" name="painLocation" placeholder="مثلاً: کمر، زانو، شانه" value="<?= e($_POST['painLocation'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-rose-400 outline-none transition"></div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">شدت درد از ۰ تا ۱۰: <span id="painValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">0</span></label>
                            <input type="range" id="painLevel" name="painLevel" min="0" max="10" step="1" value="<?= e($_POST['painLevel'] ?? '0') ?>" class="w-full mt-3.5">
                            <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — بدون درد</span><span>۱۰ — شدیدترین</span></div>
                        </div>
                    </div>
                    <div><label for="painTriggers" class="block text-sm font-medium text-zinc-300 mb-2">چه حرکاتی درد را بیشتر می‌کند؟ <span class="text-yellow-400">*</span></label><input type="text" id="painTriggers" name="painTriggers" placeholder="مثلاً: خم شدن به جلو، نشستن طولانی" value="<?= e($_POST['painTriggers'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-rose-400 outline-none transition"></div>
                </div>
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">سابقه: <span class="text-yellow-400">*</span></legend>
                    <div class="flex flex-wrap gap-2.5"><?php foreach ($medicalList as $m): ?><label class="cursor-pointer"><input type="checkbox" name="medicalHistory[]" value="<?= e($m) ?>" class="peer sr-only" <?= isCheckedArr('medicalHistory', $m) ?>><span class="inline-flex items-center gap-2 text-sm rounded-full px-4 py-2.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-rose-500 peer-checked:text-white peer-checked:font-bold peer-checked:ring-rose-500 peer-checked:shadow-lg peer-checked:shadow-rose-500/25 hover:ring-rose-400/60 [&_svg]:opacity-0 peer-checked:[&_svg]:opacity-100 [&_svg]:transition-opacity"><i data-lucide="check" class="w-3.5 h-3.5"></i><?= e($m) ?></span></label><?php endforeach; ?></div>
                </fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="utensils" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">تغذیه</h2><p class="text-xs text-zinc-500">بخش ۶ از ۸</p></div></div>
            <div class="space-y-5">
                <div><label for="meals" class="block text-sm font-medium text-zinc-300 mb-2">تعداد وعده‌های غذایی در روز <span class="text-yellow-400">*</span></label><input type="number" id="meals" name="meals" min="1" max="8" placeholder="مثلاً: ۳" required value="<?= e($_POST['meals'] ?? '') ?>" class="w-full sm:w-48 rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label for="favFoods" class="block text-sm font-medium text-zinc-300 mb-2">غذاهای مورد علاقه <span class="text-yellow-400">*</span></label><input type="text" id="favFoods" name="favFoods" placeholder="مثلاً: مرغ، برنج، ماهی" required value="<?= e($_POST['favFoods'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="dislikedFoods" class="block text-sm font-medium text-zinc-300 mb-2">غذاهایی که مصرف نمی‌کنید <span class="text-yellow-400">*</span></label><input type="text" id="dislikedFoods" name="dislikedFoods" placeholder="مثلاً: جگر، غذاهای تند (اگر همه را می‌خورید: هیچ‌کدام)" required value="<?= e($_POST['dislikedFoods'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-5 items-end">
                    <fieldset>
                        <legend class="text-sm font-medium text-zinc-300 mb-3">مکمل مصرف می‌کنید؟ <span class="text-yellow-400">*</span></legend>
                        <div class="flex gap-2.5">
                            <label class="cursor-pointer flex-1"><input type="radio" name="supplements" value="بله" class="peer sr-only" <?= isChecked('supplements', 'بله') ?> required><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60">بله</span></label>
                            <label class="cursor-pointer flex-1"><input type="radio" name="supplements" value="خیر" class="peer sr-only" <?= isChecked('supplements', 'خیر') ?>><span class="block text-center text-sm rounded-xl px-3 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-zinc-600 peer-checked:text-white peer-checked:font-bold peer-checked:ring-zinc-500 peer-checked:shadow-lg peer-checked:shadow-black/40 hover:ring-zinc-500">خیر</span></label>
                        </div>
                    </fieldset>
                    <div><label for="supplementNames" class="block text-sm font-medium text-zinc-300 mb-2">اگر بله، نام مکمل‌ها <span class="text-yellow-400">*</span></label><input type="text" id="supplementNames" name="supplementNames" placeholder="مثلاً: پروتئین وی، کراتین" value="<?= e($_POST['supplementNames'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="dumbbell" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">امکانات تمرینی</h2><p class="text-xs text-zinc-500">بخش ۷ از ۸</p></div></div>
            <fieldset>
                <legend class="text-sm font-medium text-zinc-300 mb-3">چه تجهیزاتی در اختیار دارید؟ <span class="text-yellow-400">*</span></legend>
                <div class="space-y-2.5"><?php foreach ($equipmentOptions as $eq): ?><label class="cursor-pointer block"><input type="radio" name="equipment" value="<?= e($eq) ?>" class="peer sr-only" <?= isChecked('equipment', $eq) ?> <?= $eq === $equipmentOptions[0] ? 'required' : '' ?>><span class="flex items-center gap-3 text-sm rounded-xl px-4 py-3.5 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/50"><i data-lucide="chevron-left" class="w-4 h-4 opacity-50"></i><?= e($eq) ?></span></label><?php endforeach; ?></div>
            </fieldset>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="image" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">مدارک اولیه</h2><p class="text-xs text-zinc-500">بخش ۸ از ۸</p></div></div>
            <p class="text-sm text-zinc-400 mb-4 flex items-center gap-2"><i data-lucide="info" class="w-4 h-4 text-yellow-400"></i>لطفاً مشخص کنید کدام موارد را در اختیار دارید:</p>
            <div class="space-y-2.5"><?php foreach ($documents as $key => $label): ?><div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 rounded-xl ring-1 ring-zinc-800 bg-zinc-950/60 px-4 py-3"><span class="text-sm font-medium text-zinc-200 flex items-center gap-2"><i data-lucide="file-image" class="w-4 h-4 text-yellow-400"></i><?= e($label) ?> <span class="text-yellow-400">*</span></span><div class="flex gap-2 sm:ms-auto"><?php haveRadios($key); ?></div></div><?php endforeach; ?></div>
        </section>

        <button type="submit" id="submitBtn" class="rise rise-4 w-full inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 text-black text-lg font-extrabold px-8 py-4 shadow-xl shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 active:translate-y-0 transition"><i data-lucide="save" class="w-5 h-5"></i><span id="btnText">ثبت فرم و ساخت فایل</span></button>
    </form>

    <?php elseif ($slug === 'form2'): ?>
    <!-- ══════════ فرم ۲ ══════════ -->
    <form method="post" action="<?= e(formUrl($slug)) ?>" id="surveyForm" class="space-y-6">
        <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="user" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">اطلاعات تماس</h2><p class="text-xs text-zinc-500">بخش ۱ از ۷</p></div></div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div><label for="fullname" class="block text-sm font-medium text-zinc-300 mb-2">نام و نام خانوادگی <span class="text-yellow-400">*</span></label><input type="text" id="fullname" name="fullname" required placeholder="مثلاً: علی رضایی" value="<?= e($_POST['fullname'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <div><label for="phone" class="block text-sm font-medium text-zinc-300 mb-2">شماره تماس <span class="text-yellow-400">*</span></label><input type="tel" id="phone" name="phone" required dir="ltr" placeholder="09121234567" pattern="[0-9\-\+\s]{8,16}" value="<?= e($_POST['phone'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition text-left"></div>
                <div><label class="block text-sm font-medium text-zinc-300 mb-2">تاریخ تولد (شمسی) <span class="text-yellow-400">*</span></label><?php printJalaliDateInputs(); ?><p class="mt-2 text-xs text-zinc-500 flex items-center gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i>سن شما به‌صورت خودکار محاسبه می‌شود.</p></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label for="height" class="block text-sm font-medium text-zinc-300 mb-2">قد (cm) <span class="text-yellow-400">*</span></label><input type="number" id="height" name="height" min="100" max="230" required placeholder="۱۷۵" value="<?= e($_POST['height'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="weight" class="block text-sm font-medium text-zinc-300 mb-2">وزن (kg) <span class="text-yellow-400">*</span></label><input type="number" id="weight" name="weight" min="25" max="250" step="0.5" required placeholder="۷۵" value="<?= e($_POST['weight'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <div class="sm:col-span-2"><label for="freeHours" class="block text-sm font-medium text-zinc-300 mb-2">ساعت‌های آزاد برای تمرین <span class="text-yellow-400">*</span></label><input type="text" id="freeHours" name="freeHours" required placeholder="مثلاً: صبح‌ها ۷ تا ۹ / عصرها بعد از ۱۸" value="<?= e($_POST['freeHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
            </div>
            <fieldset class="mt-6"><legend class="text-sm font-medium text-zinc-300 mb-3">نحوه آشنایی با سعید کلاب: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('howKnow', $f2_howKnow); ?></div></fieldset>
        </section>

        <section class="rise rise-3 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="target" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">هدف اصلی مراجعه</h2><p class="text-xs text-zinc-500">بخش ۲ از ۷</p></div></div>
            <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">هدف شما چیست؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('goals2', $f2_goals); ?></div></fieldset>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="briefcase" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">سبک زندگی و سابقه ورزشی</h2><p class="text-xs text-zinc-500">بخش ۳ از ۷</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">سابقه ورزشی (تمرین در هفته): <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('sportHist2', $f2_sportHistory); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">وضعیت شغلی و سبک زندگی: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('jobStyle', $f2_jobStyle); ?></div></fieldset>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div><label for="sitHours" class="block text-sm font-medium text-zinc-300 mb-2">نشستن روزانه (ساعت) <span class="text-yellow-400">*</span></label><input type="number" id="sitHours" name="sitHours" min="0" max="20" required placeholder="۸" value="<?= e($_POST['sitHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="walkHours" class="block text-sm font-medium text-zinc-300 mb-2">پیاده‌روی روزانه (ساعت) <span class="text-yellow-400">*</span></label><input type="number" id="walkHours" name="walkHours" min="0" max="12" step="0.5" required placeholder="۱" value="<?= e($_POST['walkHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="sleepHours" class="block text-sm font-medium text-zinc-300 mb-2">خواب شبانه (ساعت) <span class="text-yellow-400">*</span></label><input type="number" id="sleepHours" name="sleepHours" min="0" max="14" step="0.5" required placeholder="۷" value="<?= e($_POST['sleepHours'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">سطح استرس شما چقدر است؟ <span class="text-yellow-400">*</span> <span id="stressValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۵</span></label>
                    <input type="range" id="stressLvl" name="stressLvl" min="0" max="10" step="1" value="<?= e($_POST['stressLvl'] ?? '5') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — بدون استرس</span><span>۱۰ — بسیار زیاد</span></div>
                </div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="stethoscope" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">سوابق پزشکی</h2><p class="text-xs text-zinc-500">بخش ۴ از ۷</p></div></div>
            <div class="space-y-5">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا تاکنون پزشک بیماری خاصی برای شما تشخیص داده است؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('diseases', $f2_diseases); ?></div></fieldset>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label for="medications" class="block text-sm font-medium text-zinc-300 mb-2">دارو یا مکمل خاصی مصرف می‌کنید؟ <span class="text-yellow-400">*</span></label><input type="text" id="medications" name="medications" required placeholder="نام دارو/مکمل — اگر ندارید: ندارم" value="<?= e($_POST['medications'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="surgery" class="block text-sm font-medium text-zinc-300 mb-2">سابقه جراحی <span class="text-yellow-400">*</span></label><input type="text" id="surgery" name="surgery" required placeholder="نوع و سال جراحی — اگر ندارید: ندارم" value="<?= e($_POST['surgery'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div class="sm:col-span-2"><label for="restriction" class="block text-sm font-medium text-zinc-300 mb-2">آیا پزشک یا فیزیوتراپ محدودیتی اعلام کرده؟ <span class="text-yellow-400">*</span></label><input type="text" id="restriction" name="restriction" required placeholder="مثلاً: ممنوعیت اسکات سنگین — اگر نیست: ندارم" value="<?= e($_POST['restriction'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="heart-pulse" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">درد و محدودیت حرکتی</h2><p class="text-xs text-zinc-500">بخش ۵ از ۷</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا درد یا آسیب دارید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('painAreas', $f2_painAreas); ?></div></fieldset>
                <div id="painDetails2" class="hidden space-y-5 rounded-2xl bg-rose-500/5 ring-1 ring-rose-500/20 p-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">شدت درد از ۰ تا ۱۰: <span id="painValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۰</span></label>
                            <input type="range" id="painLevel" name="painLevel" min="0" max="10" step="1" value="<?= e($_POST['painLevel'] ?? '0') ?>" class="w-full mt-3.5">
                            <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — بدون درد</span><span>۱۰ — شدیدترین</span></div>
                        </div>
                        <div><label for="painStart" class="block text-sm font-medium text-zinc-300 mb-2">درد از چه زمانی شروع شده؟ <span class="text-yellow-400">*</span></label><input type="text" id="painStart" name="painStart" placeholder="مثلاً: از ۶ ماه پیش" value="<?= e($_POST['painStart'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-rose-400 outline-none transition"></div>
                    </div>
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا تحت درمان هستید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php foreach (['بله', 'خیر'] as $t): ?><label class="cursor-pointer flex-1 max-w-[9rem]"><input type="radio" name="treatment" value="<?= e($t) ?>" class="peer sr-only" <?= isChecked('treatment', $t) ?>><span class="block text-center text-sm rounded-xl px-4 py-3 ring-1 ring-zinc-700 bg-zinc-800/60 text-zinc-300 transition peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:font-bold peer-checked:ring-yellow-400 peer-checked:shadow-lg peer-checked:shadow-yellow-500/25 hover:ring-yellow-400/60"><?= e($t) ?></span></label><?php endforeach; ?></div></fieldset>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا انجام فعالیت‌های زیر برایتان دشوار است؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('difficulties', $f2_difficulties); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="moon" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">عادات روزانه و تغذیه</h2><p class="text-xs text-zinc-500">بخش ۶ از ۷</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">کیفیت خواب شما چطور است؟ <span class="text-yellow-400">*</span></legend><div class="space-y-2.5"><?php radioGroup('sleepQuality', $f2_sleepQuality, true); ?></div></fieldset>
                <div class="grid sm:grid-cols-2 gap-5">
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">مصرف دخانیات: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('smoking', $f2_smoking); ?></div></fieldset>
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">مصرف مشروبات الکلی: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('alcohol', $f2_alcohol); ?></div></fieldset>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-zinc-300 mb-2">میزان مصرف آب روزانه <span id="waterValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۳</span></label><input type="range" id="water" name="water" min="1" max="5" step="1" value="<?= e($_POST['water'] ?? '3') ?>" class="w-full mt-3.5"><div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۱ — خیلی کم</span><span>۵ — عالی</span></div></div>
                    <div><label class="block text-sm font-medium text-zinc-300 mb-2">میانگین مصرف میوه و سبزی <span id="fruitsValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۳</span></label><input type="range" id="fruits" name="fruits" min="1" max="5" step="1" value="<?= e($_POST['fruits'] ?? '3') ?>" class="w-full mt-3.5"><div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۱ — خیلی کم</span><span>۵ — عالی</span></div></div>
                </div>
                <div><label for="diet" class="block text-sm font-medium text-zinc-300 mb-2">آیا رژیم غذایی خاصی دارید؟ <span class="text-yellow-400">*</span></label><input type="text" id="diet" name="diet" required placeholder="مثلاً: کتوژنیک، گیاه‌خواری — اگر ندارید: ندارم" value="<?= e($_POST['diet'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="sparkles" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">خدمات مورد نظر</h2><p class="text-xs text-zinc-500">بخش ۷ از ۷</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">انتخاب خدمات مورد نظر: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('services', $f2_services); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا قصد شروع در همین ماه را دارید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('startMonth', $f2_startMonth); ?></div></fieldset>
                <div><label for="oneProblem" class="block text-sm font-medium text-zinc-300 mb-2">اگر فقط یک مشکل جسمانی شما طی سه ماه آینده برطرف شود، دوست دارید آن مشکل چه باشد؟ <span class="text-yellow-400">*</span></label><textarea id="oneProblem" name="oneProblem" rows="4" maxlength="600" required placeholder="مثلاً: کمردردم کامل از بین بره…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['oneProblem'] ?? '') ?></textarea></div>
            </div>
        </section>

        <button type="submit" id="submitBtn" class="rise rise-4 w-full inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 text-black text-lg font-extrabold px-8 py-4 shadow-xl shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 active:translate-y-0 transition"><i data-lucide="send" class="w-5 h-5"></i><span id="btnText">ارسال فرم مشاوره</span></button>
    </form>

    <?php elseif ($slug === 'form3'): ?>
    <!-- ══════════ فرم ۳ ══════════ -->
    <form method="post" action="<?= e(formUrl($slug)) ?>" id="surveyForm" class="space-y-6">
        <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <label for="fullname" class="block text-sm font-medium text-zinc-300 mb-2">نام و نام خانوادگی <span class="text-yellow-400">*</span></label>
            <input type="text" id="fullname" name="fullname" required placeholder="مثلاً: علی رضایی" value="<?= e($_POST['fullname'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition">
            <p class="mt-2 text-xs text-zinc-500 flex items-center gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i>فایل پاسخ شما با «نام و نام خانوادگی کامل» در پوشه مخصوص این فرم ساخته می‌شود.</p>
        </section>

        <section class="rise rise-3 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="trending-up" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">تغییرات و پیشرفت</h2><p class="text-xs text-zinc-500">بخش ۱ از ۵</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">از شروع همکاری چه تغییراتی احساس کرده‌اید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('changes', $f3_changes); ?></div></fieldset>
                <div><label for="otherChange" class="block text-sm font-medium text-zinc-300 mb-2">تغییر دیگری:</label><input type="text" id="otherChange" name="otherChange" placeholder="اگر تغییر دیگری داشته‌اید، اینجا بنویسید (اختیاری)" value="<?= e($_POST['otherChange'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">میزان پیشرفت خود را از ۰ تا ۱۰ ارزیابی کنید: <span class="text-yellow-400">*</span> <span id="progressValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۵</span></label>
                    <input type="range" id="progress" name="progress" min="0" max="10" step="1" value="<?= e($_POST['progress'] ?? '5') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — بدون پیشرفت</span><span>۱۰ — عالی</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا به هدف اولیه خود نزدیک‌تر شده‌اید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('goalCloser', $f3_goalProgress); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="star" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">کیفیت خدمات</h2><p class="text-xs text-zinc-500">بخش ۲ از ۵</p></div></div>
            <div class="space-y-6">
                <?php foreach ($f3_quality as $key => $label): ?>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2"><?= e($label) ?>: <span id="<?= e($key) ?>Value" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۵</span></label>
                    <input type="range" id="<?= e($key) ?>" name="<?= e($key) ?>" min="0" max="10" step="1" value="<?= e($_POST[$key] ?? '5') ?>" class="w-full mt-3.5 quality-range" data-out="<?= e($key) ?>Value">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — ضعیف</span><span>۱۰ — عالی</span></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="heart-handshake" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">ارزش همکاری</h2><p class="text-xs text-zinc-500">بخش ۳ از ۵</p></div></div>
            <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">مهم‌ترین دلیل ادامه همکاری شما چیست؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('reasons', $f3_reasons); ?></div></fieldset>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="lightbulb" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">بهبود سرویس</h2><p class="text-xs text-zinc-500">بخش ۴ از ۵</p></div></div>
            <div class="space-y-5">
                <div><label for="keepThing" class="block text-sm font-medium text-zinc-300 mb-2">چه چیزی در خدمات Saeid Club را دوست دارید و نباید تغییر کند؟ <span class="text-yellow-400">*</span></label><textarea id="keepThing" name="keepThing" rows="3" maxlength="600" required placeholder="مثلاً: پیگیری روزانه مربی…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['keepThing'] ?? '') ?></textarea></div>
                <div><label for="improveThing" class="block text-sm font-medium text-zinc-300 mb-2">چه چیزی را می‌توانیم بهتر کنیم؟ <span class="text-yellow-400">*</span></label><textarea id="improveThing" name="improveThing" rows="3" maxlength="600" required placeholder="نظر صادقانه شما به ما کمک می‌کند…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['improveThing'] ?? '') ?></textarea></div>
                <div><label for="oneChange" class="block text-sm font-medium text-zinc-300 mb-2">اگر فقط یک تغییر بتوانیم ایجاد کنیم، دوست دارید چه چیزی باشد؟ <span class="text-yellow-400">*</span></label><textarea id="oneChange" name="oneChange" rows="3" maxlength="600" required placeholder="مهم‌ترین درخواست شما…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['oneChange'] ?? '') ?></textarea></div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="megaphone" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">معرفی به دیگران</h2><p class="text-xs text-zinc-500">بخش ۵ از ۵</p></div></div>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">از ۰ تا ۱۰ چقدر احتمال دارد Saeid Club Online را به دوستان خود معرفی کنید؟ <span class="text-yellow-400">*</span> <span id="npsValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۸</span></label>
                    <input type="range" id="npsScore" name="npsScore" min="0" max="10" step="1" value="<?= e($_POST['npsScore'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — اصلاً</span><span>۱۰ — حتماً</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">وضعیت معرفی: <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('recommend', $f3_recommend); ?></div></fieldset>
            </div>
        </section>

        <button type="submit" id="submitBtn" class="rise rise-4 w-full inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 text-black text-lg font-extrabold px-8 py-4 shadow-xl shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 active:translate-y-0 transition"><i data-lucide="send" class="w-5 h-5"></i><span id="btnText">ثبت ارزیابی</span></button>
    </form>

    <?php elseif ($slug === 'form4'): ?>
    <!-- ══════════ فرم ۴ ══════════ -->
    <form method="post" action="<?= e(formUrl($slug)) ?>" id="surveyForm" class="space-y-6">
        <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="building-2" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">اطلاعات فردی و سازمانی</h2><p class="text-xs text-zinc-500">بخش ۱ از ۴</p></div></div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div><label for="fullname" class="block text-sm font-medium text-zinc-300 mb-2">نام و نام خانوادگی <span class="text-yellow-400">*</span></label><input type="text" id="fullname" name="fullname" required placeholder="مثلاً: علی رضایی" value="<?= e($_POST['fullname'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <div><label for="phone" class="block text-sm font-medium text-zinc-300 mb-2">شماره تماس <span class="text-yellow-400">*</span></label><input type="tel" id="phone" name="phone" required dir="ltr" inputmode="tel" pattern="[0-9\-\+\s]{8,16}" placeholder="09121234567" value="<?= e($_POST['phone'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition text-left"></div>
                <div><label class="block text-sm font-medium text-zinc-300 mb-2">تاریخ تولد (شمسی) <span class="text-yellow-400">*</span></label><?php printJalaliDateInputs(); ?><p class="mt-2 text-xs text-zinc-500 flex items-center gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i>سن شما به‌صورت خودکار محاسبه می‌شود.</p></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label for="height" class="block text-sm font-medium text-zinc-300 mb-2">قد (cm) <span class="text-yellow-400">*</span></label><input type="number" id="height" name="height" min="100" max="230" required placeholder="۱۷۵" value="<?= e($_POST['height'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="weight" class="block text-sm font-medium text-zinc-300 mb-2">وزن (kg) <span class="text-yellow-400">*</span></label><input type="number" id="weight" name="weight" min="25" max="250" step="0.5" required placeholder="۸۰" value="<?= e($_POST['weight'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <div><label for="organization" class="block text-sm font-medium text-zinc-300 mb-2">نام سازمان یا اداره شما <span class="text-yellow-400">*</span></label><input type="text" id="organization" name="organization" required placeholder="نام سازمان را بنویسید" value="<?= e($_POST['organization'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <div><label for="departmentRole" class="block text-sm font-medium text-zinc-300 mb-2">واحد محل خدمت و سمت سازمانی <span class="text-yellow-400">*</span></label><input type="text" id="departmentRole" name="departmentRole" required placeholder="مثلاً: واحد مالی، کارشناس ارشد" value="<?= e($_POST['departmentRole'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
            </div>
            <div class="mt-6 space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">نوع شغل <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('jobType', $f4_jobTypes); ?></div></fieldset>
                <div><label for="deskHours" class="block text-sm font-medium text-zinc-300 mb-2">میزان نشستن پشت میز روزانه چند ساعت است؟ <span class="text-yellow-400">*</span></label><input type="number" id="deskHours" name="deskHours" min="0" max="20" step="0.5" required placeholder="مثلاً: ۸" value="<?= e($_POST['deskHours'] ?? '') ?>" class="w-full sm:w-52 rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
            </div>
        </section>

        <section class="rise rise-3 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="target" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">هدف و سابقه فعالیت</h2><p class="text-xs text-zinc-500">بخش ۲ از ۴</p></div></div>
            <div class="space-y-6">
                <fieldset>
                    <legend class="text-sm font-medium text-zinc-300 mb-3">مهم‌ترین هدف شما چیست؟ <span class="text-yellow-400">*</span> <span class="text-xs text-zinc-500">(حداکثر ۳ مورد)</span></legend>
                    <div class="flex flex-wrap gap-2.5" id="orgGoalsGroup"><?php checkGroup('orgGoals', $f4_goals); ?></div>
                    <p id="orgGoalsHint" class="mt-3 text-xs text-yellow-400">۰ از ۳ انتخاب شده</p>
                </fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">سطح فعالیت فعلی <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('activityLevel', $f4_activityLevels); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">سابقه تمرین <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('trainingHist4', $f4_trainingHistory); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">نوع ورزش <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('sports4', $f4_sports); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="heart-pulse" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">وضعیت پزشکی و درد</h2><p class="text-xs text-zinc-500">بخش ۳ از ۴</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا پزشک تاکنون یکی از بیماری‌های زیر را برای شما تشخیص داده است؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('diseases4', $f4_diseases); ?></div></fieldset>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label for="medications4" class="block text-sm font-medium text-zinc-300 mb-2">آیا دارو یا مکمل خاصی مصرف می‌کنید؟ <span class="text-yellow-400">*</span></label><input type="text" id="medications4" name="medications4" required placeholder="اگر مصرف نمی‌کنید: ندارم" value="<?= e($_POST['medications4'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                    <div><label for="surgery4" class="block text-sm font-medium text-zinc-300 mb-2">سابقه جراحی <span class="text-yellow-400">*</span></label><input type="text" id="surgery4" name="surgery4" required placeholder="اگر سابقه ندارید: ندارم" value="<?= e($_POST['surgery4'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا در حال حاضر درد دارید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('hasPain4', ['بله', 'خیر']); ?></div></fieldset>
                <div id="painDetails4" class="<?= (($_POST['hasPain4'] ?? '') === 'بله') ? '' : 'hidden' ?> space-y-6 rounded-2xl bg-rose-500/5 ring-1 ring-rose-500/20 p-5">
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">محل درد <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('painAreas4', $f4_painAreas); ?></div></fieldset>
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">شدت درد از ۰ تا ۱۰ <span id="painLevel4Value" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۰</span></label>
                        <input type="range" id="painLevel4" name="painLevel4" min="0" max="10" step="1" value="<?= e($_POST['painLevel4'] ?? '0') ?>" class="w-full mt-3.5">
                        <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۰ — بدون درد</span><span>۱۰ — شدیدترین</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="moon-star" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">خواب و سبک زندگی</h2><p class="text-xs text-zinc-500">بخش ۴ از ۴</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">کیفیت خواب شما چطور است؟ <span class="text-yellow-400">*</span></legend><div class="space-y-2.5"><?php radioGroup('sleepQuality4', $f4_sleepQuality, true); ?></div></fieldset>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">سطح استرس از ۱ تا ۵ <span id="stressLevel4Value" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold fa-num mr-1">۳</span></label>
                    <input type="range" id="stressLevel4" name="stressLevel4" min="1" max="5" step="1" value="<?= e($_POST['stressLevel4'] ?? '3') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5 fa-num"><span>۱ — کم</span><span>۵ — زیاد</span></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">مصرف دخانیات <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('smoking4', $f4_smoking); ?></div></fieldset>
                    <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">مصرف مشروبات الکلی <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('alcohol4', $f4_alcohol); ?></div></fieldset>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">آیا در بالا بردن دست‌ها مشکل دارید؟ <span class="text-yellow-400">*</span></legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('raiseArms4', ['بله', 'خیر']); ?></div></fieldset>
            </div>
        </section>

        <button type="submit" id="submitBtn" class="rise rise-4 w-full inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 text-black text-lg font-extrabold px-8 py-4 shadow-xl shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 active:translate-y-0 transition"><i data-lucide="send" class="w-5 h-5"></i><span id="btnText">ارسال فرم مشاوره سازمانی</span></button>
    </form>

    <?php elseif ($slug === 'form5'): ?>
    <!-- ══════════ فرم ۵ (کاملاً اختیاری) ══════════ -->
    <form method="post" action="<?= e(formUrl($slug)) ?>" id="surveyForm" class="opt-form space-y-6">
        <section class="rise rise-2 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="id-card" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">مشخصات عضویت</h2><p class="text-xs text-zinc-500">بخش ۱ از ۸</p></div></div>
            <div class="space-y-6">
                <div><label for="fullname" class="block text-sm font-medium text-zinc-300 mb-2">نام و نام خانوادگی <span class="text-xs text-zinc-500">(اختیاری)</span></label><input type="text" id="fullname" name="fullname" placeholder="اگر دوست دارید نامتان را بنویسید" value="<?= e($_POST['fullname'] ?? '') ?>" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition"></div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">1. در حال حاضر از کدام خدمات Saeid Club استفاده می‌کنید؟</legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('services5', $f5_services); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">2. چه مدت است عضو Saeid Club هستید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('memberDuration', $f5_durations); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">3. هدف اصلی شما از حضور در Saeid Club چیست؟</legend><div class="flex flex-wrap gap-2.5"><?php checkGroup('goals5', $f5_goals); ?></div></fieldset>
            </div>
        </section>

        <section class="rise rise-3 bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="star" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">رضایت کلی از Saeid Club</h2><p class="text-xs text-zinc-500">بخش ۲ از ۸</p></div></div>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">4. در مجموع، از تجربه حضور در Saeid Club از 0 تا 10 چه نمره‌ای می‌دهید؟ <span id="satOverallValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satOverall" name="satOverall" min="0" max="10" step="1" value="<?= e($_POST['satOverall'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — اصلاً راضی نیستم</span><span>10 — فوق‌العاده</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">5. کیفیت خدمات نسبت به انتظاری که هنگام شروع داشتید چگونه بوده است؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('expect5', $f5_expect); ?></div></fieldset>
                <div><label for="scoreReason5" class="block text-sm font-medium text-zinc-300 mb-2">6. مهم‌ترین دلیل نمره‌ای که به Saeid Club دادید چیست؟</label><textarea id="scoreReason5" name="scoreReason5" rows="3" maxlength="600" placeholder="دلیل نمره‌ای که دادید…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['scoreReason'] ?? '') ?></textarea></div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="trending-up" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">نتیجه و پیشرفت</h2><p class="text-xs text-zinc-500">بخش ۳ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">7. تا چه اندازه احساس می‌کنید از زمان شروع تمرین پیشرفت کرده‌اید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('progress5', $f5_scale5a); ?></div></fieldset>
                <div><label for="bigChange5" class="block text-sm font-medium text-zinc-300 mb-2">8. مهم‌ترین تغییر مثبتی که تاکنون در بدن، سلامت یا عملکرد خود احساس کرده‌اید چیست؟</label><textarea id="bigChange5" name="bigChange" rows="3" maxlength="600" placeholder="مثلاً: کمردردم خیلی کمتر شده…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['bigChange'] ?? '') ?></textarea></div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">9. آیا ارزیابی‌ها، تست‌ها و گزارش‌های پیشرفت به شما کمک کرده‌اند تغییرات خود را بهتر ببینید و درک کنید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('assess5', $f5_scale5a); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">10. آیا می‌دانید هدف فعلی برنامه شما چیست و چرا تمرینات فعلی را انجام می‌دهید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('knowGoal5', $f5_know); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="user-check" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">برنامه و مربی</h2><p class="text-xs text-zinc-500">بخش ۴ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">11. تا چه اندازه احساس می‌کنید برنامه تمرینی شما متناسب با هدف و شرایط شخصی شما طراحی شده است؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('planFit5', $f5_scale5b); ?></div></fieldset>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">12. از عملکرد مربی یا ترینر خود از 0 تا 10 چه نمره‌ای می‌دهید؟ <span id="satCoachValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satCoach" name="satCoach" min="0" max="10" step="1" value="<?= e($_POST['satCoach'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — ضعیف</span><span>10 — عالی</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">13. مربی تا چه اندازه هنگام تمرین به اجرای صحیح حرکات و اصلاح تکنیک شما توجه می‌کند؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('attention5', $f5_scale5c); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">14. آیا احساس می‌کنید مربی اهداف، محدودیت‌ها، دردها و شرایط شما را می‌شناسد؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('knowsYou5', $f5_knows); ?></div></fieldset>
                <div><label for="coachFix5" class="block text-sm font-medium text-zinc-300 mb-2">15. اگر بخواهید فقط یک مورد در عملکرد مربی یا نحوه تمرین شما بهتر شود، آن مورد چیست؟</label><textarea id="coachFix5" name="coachFix" rows="3" maxlength="600" placeholder="مهم‌ترین پیشنهاد شما برای مربی…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['coachFix'] ?? '') ?></textarea></div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="headphones" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">نظارت و پشتیبانی Saeid Club</h2><p class="text-xs text-zinc-500">بخش ۵ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">16. آیا احساس می‌کنید روند تمرینی و پیشرفت شما توسط سیستم Saeid Club پیگیری می‌شود؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('sysFollow5', $f5_scale5b); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">17. میزان ارتباط و نظارت سعید رمضان‌پور بر روند شما را چگونه ارزیابی می‌کنید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('saeedWatch5', $f5_watch); ?></div></fieldset>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">18. وقتی سؤال، درد، مشکل یا درخواست تغییر دارید، از کیفیت پاسخ‌گویی و پیگیری تیم چقدر رضایت دارید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('teamReply5', $f5_scale5c); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="building" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">محیط، امکانات و تجربه حضور</h2><p class="text-xs text-zinc-500">بخش ۶ از ۸</p></div></div>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">19. از کیفیت و تنوع تجهیزات موردنیاز تمرین خود از 0 تا 10 چه نمره‌ای می‌دهید؟ <span id="satEquipValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satEquip" name="satEquip" min="0" max="10" step="1" value="<?= e($_POST['satEquip'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — ضعیف</span><span>10 — عالی</span></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">20. از تمیزی، بهداشت و نظم محیط Saeid Club از 0 تا 10 چه نمره‌ای می‌دهید؟ <span id="satCleanValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satClean" name="satClean" min="0" max="10" step="1" value="<?= e($_POST['satClean'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — ضعیف</span><span>10 — عالی</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">21. آیا شلوغی، کمبود فضا یا انتظار برای استفاده از تجهیزات روی کیفیت تمرین شما اثر منفی دارد؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('crowd5', $f5_crowd); ?></div></fieldset>
                <div><label for="facilityFix5" class="block text-sm font-medium text-zinc-300 mb-2">22. اگر بتوانیم یک مورد در محیط، امکانات یا تجهیزات باشگاه بهتر کنیم، پیشنهاد شما چیست؟</label><textarea id="facilityFix5" name="facilityFix" rows="3" maxlength="600" placeholder="پیشنهاد بهبود امکانات…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['facilityFix'] ?? '') ?></textarea></div>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="gem" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">ارزش خدمات و احتمال ادامه عضویت</h2><p class="text-xs text-zinc-500">بخش ۷ از ۸</p></div></div>
            <div class="space-y-6">
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">23. با توجه به مبلغی که پرداخت می‌کنید، ارزش خدمات دریافتی را چگونه ارزیابی می‌کنید؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('valueMoney5', $f5_value); ?></div></fieldset>
                <div><label for="valuedPart5" class="block text-sm font-medium text-zinc-300 mb-2">24. کدام بخش از خدمات Saeid Club برای شما بیشترین ارزش را دارد؟</label><textarea id="valuedPart5" name="valuedPart5" rows="3" maxlength="600" placeholder="ارزشمندترین بخش خدمات برای شما…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['valuedPart5'] ?? '') ?></textarea></div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">25. اگر امروز زمان تمدید باشد، احتمال ادامه عضویت شما از 0 تا 10 چقدر است؟ <span id="satRenewValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satRenew" name="satRenew" min="0" max="10" step="1" value="<?= e($_POST['satRenew'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — اصلاً</span><span>10 — حتماً</span></div>
                </div>
                <fieldset><legend class="text-sm font-medium text-zinc-300 mb-3">26. مهم‌ترین عاملی که ممکن است باعث شود در آینده عضویت خود را ادامه ندهید چیست؟</legend><div class="flex flex-wrap gap-2.5"><?php radioGroup('quitReason5', $f5_quit); ?></div></fieldset>
            </div>
        </section>

        <section class="bg-zinc-900/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/50 ring-1 ring-zinc-800 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6"><span class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-300 to-amber-500 text-black grid place-items-center shadow-lg shadow-yellow-500/25"><i data-lucide="award" class="w-5 h-5"></i></span><div><h2 class="font-bold text-lg text-zinc-100">وفاداری، نقاط قوت و پیشنهادها</h2><p class="text-xs text-zinc-500">بخش ۸ از ۸</p></div></div>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">27. از 0 تا 10 چقدر احتمال دارد Saeid Club را به دوست، خانواده یا همکار خود پیشنهاد کنید؟ <span id="satNpsValue" class="inline-grid place-items-center min-w-8 h-8 rounded-lg bg-yellow-400 text-black text-sm font-bold mr-1">8</span></label>
                    <input type="range" id="satNps" name="satNps" min="0" max="10" step="1" value="<?= e($_POST['satNps'] ?? '8') ?>" class="w-full mt-3.5">
                    <div class="flex justify-between text-[11px] text-zinc-500 mt-1.5"><span>0 — اصلاً</span><span>10 — حتماً</span></div>
                </div>
                <div><label for="strength5" class="block text-sm font-medium text-zinc-300 mb-2">28. به نظر شما مهم‌ترین نقطه قوت Saeid Club چیست؟</label><textarea id="strength5" name="strength5" rows="3" maxlength="600" placeholder="بزرگ‌ترین نقطه قوت باشگاه از نگاه شما…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['strength5'] ?? '') ?></textarea></div>
                <div><label for="weakness5" class="block text-sm font-medium text-zinc-300 mb-2">29. به نظر شما مهم‌ترین نقطه ضعف یا بخشی که باید بهتر شود چیست؟</label><textarea id="weakness5" name="weakness5" rows="3" maxlength="600" placeholder="صادقانه بنویسید…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['weakness5'] ?? '') ?></textarea></div>
                <div><label for="oneChange5" class="block text-sm font-medium text-zinc-300 mb-2">30. اگر مدیریت Saeid Club فقط بتواند یک تغییر برای بهتر شدن تجربه شما ایجاد کند، دوست دارید آن تغییر چه باشد؟</label><textarea id="oneChange5" name="oneChange5" rows="4" maxlength="600" placeholder="مهم‌ترین تغییر موردنظر شما…" class="w-full rounded-xl border-0 ring-1 ring-zinc-700 bg-zinc-800/60 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:ring-2 focus:ring-yellow-400 outline-none transition resize-y leading-7"><?= e($_POST['oneChange5'] ?? '') ?></textarea></div>
            </div>
        </section>

        <button type="submit" id="submitBtn" class="rise rise-4 w-full inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-l from-yellow-300 via-amber-400 to-yellow-500 text-black text-lg font-extrabold px-8 py-4 shadow-xl shadow-yellow-500/25 hover:shadow-yellow-500/40 hover:-translate-y-0.5 active:translate-y-0 transition"><i data-lucide="send" class="w-5 h-5"></i><span id="btnText">ثبت نظرسنجی</span></button>
    </form>
    <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
