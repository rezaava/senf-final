<!-- Sidebar - نهایی با جابجایی کامل محتوا -->
    <style>
        .submenu .nav-link {
            font-size: 0.9rem;
            /* کوچیک‌تر از متن اصلی */
            padding-left: 1.5rem;
            /* کمی فاصله برای زیبایی */
        }

        #add-list:hover {
            width: 90%;
        }

        #show-list:hover {
            width: 90%;
        }
    </style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo" id="sidebarLogo">
            <i class="fas fa-store"></i>
            <span class="sidebar-title">فروشگاه</span>
        </div>
        <button class="close-offcanvas d-md-none" id="closeOffcanvasBtn"><i class="fas fa-times"></i></button>
    </div>

    <div class="sidebarmobile w-100 pt-1 text-center">
        <p class="sidebar-item shadow sidebarmobilebtn">
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
                <i class="fa-solid fa-bars"></i>
            </button>
        </p>
        @if (Auth::user())
            <p class="sidebar-item shadow p-2">
                <a href="{{ route('user.profile') }}" class="d-flex justify-content-start align-items-center"
                    style="@if (Route::currentRouteName() == 'user.profile') color:#0b4cff; @endif">
                    <img src="" class="float-center mx-3" alt="profile" width="50px" />
                    <span class="">{{ Auth::user()->name ?? 'کاربر' }}</span>
                </a>
            </p>
            <p class="sidebar-item shadow p-2">
                <a href="/" class="d-flex justify-content-start align-items-center"
                    style="@if (Route::currentRouteName() == 'index') color:#0b4cff; @endif">
                    <i class="fa-solid fa-house mx-3"></i>
                    <span class="text">داشبورد</span>
                </a>
            </p>
            @if (Auth::user()->hasRole('user'))
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('request.list') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'home') color:#0b4cff; @endif">
                        <i class="fa-solid fa-clipboard-list mx-3"></i>
                        <span class="text">درخواست های من</span>
                    </a>
                </p>
                <div class="card accordion-card my-1">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'cooperation' or Route::currentRouteName() == 'operatorRequest') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#requests">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-user-tie ms-2"></i>
                                درخواست همکاری
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="requests" class="collapse @if (Route::currentRouteName() == 'cooperation' or Route::currentRouteName() == 'operatorRequest') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('cooperation') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'cooperation') color:#0b4cff; @endif">
                                    سالن جدید
                                </a>
                                <a href="{{ route('operatorRequest') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'operatorRequest') color:#0b4cff; @endif">
                                    اپراتور جدید
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->hasRole('manager'))
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('calende.organ', Auth::user()->organSelected) }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'calende.organ') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">تقویم کاری</span>
                    </a>
                </p>
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('orders.index') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'orders.index') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">سفارشات</span>
                    </a>
                </p>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'service.list' or Route::currentRouteName() == 'contract-templates.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#collapseOne">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                تعاریف پایه
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="collapseOne" class="collapse @if (Route::currentRouteName() == 'service.list' or Route::currentRouteName() == 'contract-templates.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('service.list') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'service.list') color:#0b4cff; @endif">
                                    خدمات
                                </a>
                                <a href="{{ route('contract-templates.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'contract-templates.index') color:#0b4cff; @endif">
                                    قرارداد ها
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" style="">
                                    دوره های آموزشی من
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'sliders.index' or
                                    Route::currentRouteName() == 'special-offers.edit' or
                                    Route::currentRouteName() == 'coupons.index' or
                                    Route::currentRouteName() == 'galleries.index' or
                                    Route::currentRouteName() == 'banners.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#majole">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                ماژول ها
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="majole" class="collapse @if (Route::currentRouteName() == 'sliders.index' or
                            Route::currentRouteName() == 'special-offers.edit' or
                            Route::currentRouteName() == 'coupons.index' or
                            Route::currentRouteName() == 'galleries.index' or
                            Route::currentRouteName() == 'banners.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('galleries.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'galleries.index') color:#0b4cff; @endif">
                                    گالری عکس و فیلم
                                </a>
                                <a href="{{ route('banners.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'banners.index') color:#0b4cff; @endif">
                                    بنر ها
                                </a>
                                <a href="{{ route('sliders.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'sliders.index') color:#0b4cff; @endif">
                                    اسلایدر ها
                                </a>
                                <a href="{{ route('special-offers.edit') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'special-offers.edit') color:#0b4cff; @endif">
                                    شگفت انگیز
                                </a>
                                <a href="{{ route('coupons.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'coupons.index') color:#0b4cff; @endif">
                                    کد تخفیف
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'transaction.index' or
                                    Route::currentRouteName() == 'reports.show' or
                                    Route::currentRouteName() == 'wallet.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#money">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-user-tie ms-2"></i>
                                مالی
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="money" class="collapse @if (Route::currentRouteName() == 'transaction.index' or
                            Route::currentRouteName() == 'reports.show' or
                            Route::currentRouteName() == 'wallet.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('transaction.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'transaction.index') color:#0b4cff; @endif">
                                    تراکنش ها
                                </a>
                                <a href="{{ route('reports.show', Auth::user()->organSelected) }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'reports.show') color:#0b4cff; @endif">
                                    گزارش مالی
                                </a>
                                <a href="{{ route('wallet.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'wallet.index') color:#0b4cff; @endif">
                                    شارژ کیف پول
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'user.list' or Route::currentRouteName() == 'tezt') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#users">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                کاربران
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="users" class="collapse @if (Route::currentRouteName() == 'user.list' or Route::currentRouteName() == 'test') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action" style="">
                                    مشتری ها
                                </a>
                                <a href="{{ route('user.list') }}" class="list-group-item list-group-item-action"
                                    style="">
                                    اپراتور ها
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'request.list' or Route::currentRouteName() == 'tickets.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#managment">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                مدیریت
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="managment" class="collapse @if (Route::currentRouteName() == 'request.list' or Route::currentRouteName() == 'tickets.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('request.list') }}" class="list-group-item list-group-item-action" style="">
                                    درخواست ها
                                </a>
                                <a href="{{ route('tickets.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'tickets.index') color:#0b4cff; @endif">
                                    تیکت ها
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" style="">
                                    نظرات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->hasRole('admin'))
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'category.list' or
                                    Route::currentRouteName() == 'educational-videos.index' or
                                    Route::currentRouteName() == 'contract-templates.index' or
                                    Route::currentRouteName() == 'site-rules.edit') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#collapseOneAdmin">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                تعاریف پایه
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="collapseOneAdmin" class="collapse @if (Route::currentRouteName() == 'category.list' or
                            Route::currentRouteName() == 'contract-templates.index' or
                            Route::currentRouteName() == 'educational-videos.index' or
                            Route::currentRouteName() == 'site-rules.edit') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('category.list') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'category.list') color:#0b4cff; @endif">
                                    دسته بندی ها
                                </a>
                                <a href="{{ route('contract-templates.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'contract-templates.index') color:#0b4cff; @endif">
                                    قرارداد ها
                                </a>
                                <a href="{{ route('site-rules.edit') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'site-rules.edit') color:#0b4cff; @endif">
                                    قوانین
                                </a>
                                <a href="{{ route('educational-videos.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'educational-videos.index') color:#0b4cff; @endif">
                                    دوره های آموزشی
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'sliders.index' or
                                    Route::currentRouteName() == 'special-offers.edit' or
                                    Route::currentRouteName() == 'coupons.index' or
                                    Route::currentRouteName() == 'galleries.index' or
                                    Route::currentRouteName() == 'banners.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#majoleAdmin">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                ماژول ها
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="majoleAdmin" class="collapse @if (Route::currentRouteName() == 'sliders.index' or
                            Route::currentRouteName() == 'special-offers.edit' or
                            Route::currentRouteName() == 'coupons.index' or
                            Route::currentRouteName() == 'galleries.index' or
                            Route::currentRouteName() == 'banners.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('galleries.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'galleries.index') color:#0b4cff; @endif">
                                    گالری عکس و فیلم
                                </a>
                                <a href="{{ route('banners.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'banners.index') color:#0b4cff; @endif">
                                    بنر ها
                                </a>
                                <a href="{{ route('sliders.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'sliders.index') color:#0b4cff; @endif">
                                    اسلایدر ها
                                </a>
                                <a href="{{ route('special-offers.edit') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'special-offers.edit') color:#0b4cff; @endif">
                                    شگفت انگیز
                                </a>
                                <a href="{{ route('coupons.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'coupons.index') color:#0b4cff; @endif">
                                    کد تخفیف
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'transaction.index' or
                                    Route::currentRouteName() == 'reports.index' or
                                    Route::currentRouteName() == 'orders.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#soled">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                فروش
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="soled" class="collapse @if (Route::currentRouteName() == 'transaction.index' or
                            Route::currentRouteName() == 'reports.index' or
                            Route::currentRouteName() == 'orders.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'orders.index') color:#0b4cff; @endif">
                                    سفارش ها
                                </a>
                                <a href="{{ route('transaction.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'transaction.index') color:#0b4cff; @endif">
                                    تراکنش ها
                                </a>
                                <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'reports.index') color:#0b4cff; @endif">
                                    گزارش مالی
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'organ.list' or Route::currentRouteName() == 'user.list') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#usersAdmin">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                کاربران
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="usersAdmin" class="collapse @if (Route::currentRouteName() == 'organ.list' or Route::currentRouteName() == 'user.list') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('user.list') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'user.list') color:#0b4cff; @endif">
                                    کاربران
                                </a>
                                <a href="{{ route('organ.list') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'organ.list') color:#0b4cff; @endif">
                                    سالن ها
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" style="">
                                    سطح و نقش
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'request.list' or
                                    Route::currentRouteName() == 'tickets.index' or
                                    Route::currentRouteName() == 'comments.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#managmentAdmin">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-coins ms-2"></i>
                                مدیریت
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="managmentAdmin" class="collapse @if (Route::currentRouteName() == 'request.list' or
                            Route::currentRouteName() == 'tickets.index' or
                            Route::currentRouteName() == 'comments.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('request.list') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'request.list') color:#0b4cff; @endif">
                                    درخواست ها
                                </a>
                                <a href="{{ route('tickets.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'tickets.index') color:#0b4cff; @endif">
                                    تیکت ها
                                </a>
                                <a href="{{ route('comments.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'comments.index') color:#0b4cff; @endif">
                                    دیدگاه ها
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('work-hour.index') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'work-hour.index') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">ساعات کاری من</span>
                    </a>
                </p>
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('calende.operator', Auth::user()) }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'calende.operator') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">تقویم کاری</span>
                    </a>
                </p>
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('orders.index') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'orders.index') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">سفارشات من</span>
                    </a>
                </p>
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('Myservices.list') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'Myservices.list') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">خدمات من</span>
                    </a>
                </p>
                <p class="sidebar-item shadow p-2">
                    <a href="{{ route('galleries.index') }}" class="d-flex justify-content-start align-items-center"
                        style="@if (Route::currentRouteName() == 'galleries.index') color:#0b4cff; @endif">
                        <i class="fa-solid fa-house mx-3"></i>
                        <span class="text">گالری عکس و فیلم</span>
                    </a>
                </p>
                <div class="card shadow accordion-card">
                    <div class="card-header rounded-4 bg-white">
                        <a class="btn d-flex justify-content-between align-items-center w-100 accordion-link"
                            style="@if (Route::currentRouteName() == 'transaction.index' or Route::currentRouteName() == 'wallet.index') color:#0b4cff; @endif"
                            data-bs-toggle="collapse" href="#moneyOperator">
                            <span class="accordion-span1">
                                <i class="fa-solid fa-user-tie ms-2"></i>
                                مالی
                            </span>
                            <span class="accordion-span2"><i class="fa-solid fa-angle-down"></i></span>
                        </a>
                    </div>
                    <div id="moneyOperator" class="collapse @if (Route::currentRouteName() == 'transaction.index' or Route::currentRouteName() == 'wallet.index') show @endif">
                        <div class="card-body accordion-card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('transaction.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'transaction.index') color:#0b4cff; @endif">
                                    گزارش مالی
                                </a>
                                <a href="{{ route('wallet.index') }}" class="list-group-item list-group-item-action"
                                    style="@if (Route::currentRouteName() == 'wallet.index') color:#0b4cff; @endif">
                                    شارژ کیف پول
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <p class="sidebar-item shadow p-2">
                <a href="{{ route('login') }}" class="d-flex justify-content-start align-items-center">
                    <i class="fa-solid fa-right-to-bracket mx-2"></i>
                    <span class="">ورود / ثبت نام</span>
                </a>
            </p>
        @endif
        <p class="sidebar-item shadow p-2">
            <a href="{{ route('logout') }}" class="d-flex justify-content-start align-items-center">
                <i class="fa-solid fa-right-from-bracket mx-3" style="color: #e74b4b;"></i>
                <span class="text text-danger">خروج</span>
            </a>
        </p>
    </div>
</div>