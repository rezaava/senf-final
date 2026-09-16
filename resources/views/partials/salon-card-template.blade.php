{{--
    قالب مشترک کارت سالن.

    هم در مرحله‌ی انتخاب سالن هنگام ورود (auth/partials/salon-step.blade.php)
    و هم در مودال «تغییر سالن» صفحه‌ی پروفایل از همین قالب استفاده می‌شود.
    جاوااسکریپت (asset/js/salon-picker.js) فقط این را clone می‌کند و
    مقادیر را با data-salon-field پر می‌کند.

    ⇦ برای تغییر ظاهر کارتِ سالن، فقط همین فایل و asset/css/salon-select.css.
--}}
<template id="salonCardTemplate">
    <div class="salon-card" role="radio" tabindex="0" aria-checked="false">
        <div class="salon-avatar" data-salon-field="avatar"></div>

        <div class="salon-body">
            <div class="salon-name" data-salon-field="name"></div>

            <div class="salon-address">
                <i class="bi bi-geo-alt"></i>
                <span data-salon-field="address"></span>
            </div>

            <div class="salon-meta">
                <span class="salon-chip">
                    <i class="bi bi-people"></i>
                    <span data-salon-field="staff"></span>
                </span>
                <span class="salon-chip is-current is-hidden" data-salon-field="current">
                    <i class="bi bi-star-fill"></i>
                    سالن فعلی شما
                </span>
            </div>
        </div>

        <div class="salon-check">
            <i class="bi bi-check-lg"></i>
        </div>
    </div>
</template>
