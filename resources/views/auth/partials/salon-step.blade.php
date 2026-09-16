{{--
    مرحله ۵: انتخاب سالن (ویژه‌ی آرایشگرانی که در بیش از یک سالن فعال هستند)

    ساختار کارت‌ها در partials/salon-card-template.blade.php است
    (مشترک با مودال «تغییر سالن» در صفحه‌ی پروفایل)
    و منطق ساخت/انتخاب/جست‌وجو در asset/js/salon-picker.js.
--}}
<div id="salonSelectionForm" class="form-step salon-step">
    <div class="form-header">
        <h5>انتخاب سالن</h5>
        <p>شما در چند سالن فعال هستید؛ سالنی را که می‌خواهید در آن کار کنید انتخاب کنید</p>
    </div>

    <button type="button" class="back-btn" id="backToRoleSelection">
        <i class="bi bi-arrow-right"></i> بازگشت
    </button>

    <div class="salon-search is-hidden" id="salonSearchBox">
        <i class="bi bi-search salon-search-icon"></i>
        <input type="text" id="salonSearchInput" placeholder="جست‌وجوی نام یا آدرس سالن..."
            autocomplete="off">
    </div>

    <div class="salon-cards" id="salonCards" role="radiogroup" aria-label="لیست سالن‌ها">
        {{-- کارت‌ها با جاوااسکریپت ساخته می‌شوند --}}
    </div>

    <div class="salon-empty is-hidden" id="salonEmptyState">
        <i class="bi bi-shop-window"></i>
        <span id="salonEmptyText">سالنی یافت نشد</span>
    </div>

    <button class="submit-btn" id="confirmSalonBtn" disabled>
        ورود به پنل
    </button>
</div>

@include('partials.salon-card-template')
