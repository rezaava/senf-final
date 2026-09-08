    <!-- Mobile Hamburger Button -->
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

    {{-- <button class="mobile-menu-btn" id="mobileMenuBtn" style="display:none;">
        <i class="fas fa-bars"></i>
    </button> --}}
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo" id="sidebarLogo">
                <i class="fas fa-store"></i>
                <span class="sidebar-title"> فروشگاه</span>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="#">
                    <i class="fas fa-home"></i>
                    <span class="menu-label">سلام</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('setting') ? 'active' : '' }}" href="#">
                    <i class="fas fa-users"></i>
                    <span class="menu-label">تنظیمات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="#">
                    <i class="fas fa-flask"></i>
                    <span class="menu-label">تنظیمات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="#">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-label">گزارشات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="#">
                    <i class="fas fa-percent"></i>
                    <span class="menu-label">درصدها</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Offcanvas Sidebar for Mobile -->
    <div class="sidebar offcanvas-mobile" id="offcanvasSidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-store"></i><span class="sidebar-title">SENF </span>
            </div>
            <button class="close-offcanvas d-md-none" id="closeOffcanvasBtn"><i class="fas fa-times"></i></button>
        </div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="/dashboard">
                    <i class="fas fa-home"></i>
                    <span class="menu-label {{ request()->routeIs('index') ? 'active' : '' }}">داشبورد</span>
                </a>
            </li>
            @if(Auth::user()->hasRole('manager'))

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calende.organ') ? 'active' : '' }}" href="{{ route('calende.organ', Auth::user()->organSelected) }}">
                        <i class="fas fa-calendar-days"></i>
                        <span class="menu-label {{ request()->routeIs('calende.organ') ? 'active' : '' }}">تقویم کاری</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="menu-label {{ request()->routeIs('orders.index') ? 'active' : '' }}"> سفارشات</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/service*') || request()->routeIs('service.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu3" role="button"
                        aria-expanded="{{ request()->is('admin/service*') ? 'true' : 'false' }}" aria-controls="productMenu3">
                        <i class="fa-solid fa-boxes-stacked me-2"></i>
                        <span class="menu-label {{ request()->is('admin/service*') || request()->routeIs('service.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'active' : '' }}">تعاریف پایه</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/service*') || request()->routeIs('service.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'show' : '' }}" id="productMenu3"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('service.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('service.list') ? 'active1' : '' }}"
                                    href="{{ route('service.list') }}" id="show-list">    خدمات</a>
                            </li>
                             <li class="nav-item {{ request()->routeIs('contract-templates.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('contract-templates.index') ? 'active1' : '' }}"
                                    href="{{ route('contract-templates.index') }}" id="add-list">قرارداد ها </a>
                            </li>

                            {{-- <li class="nav-item {{ request()->routeIs('site-rules.edit') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('site-rules.edit') ? 'active1' : '' }}"
                                    href="{{ route('site-rules.edit') }}" id="add-list"> قوانین </a>
                            </li> --}}

                            <li class="nav-item {{ request()->routeIs('educational-videos.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('educational-videos.index') ? 'active1' : '' }}"
                                    href="#" id="add-list"> دوره های آموزشی من </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') || request()->routeIs('coupons.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu2" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu2">
                        <i class="fa-solid fa-ticket"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') 
                    || request()->routeIs('coupons.index') ? 'active' : '' }}"">ماژول ها</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') 
                    || request()->routeIs('coupons.index') ? 'show' : '' }}" id="productMenu2"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('galleries.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('galleries.index') ? 'active1' : '' }}"
                                    href="{{ route('galleries.index') }}" id="show-list">  گالری عکس و فیلم</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('banners.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('banners.index') ? 'active1' : '' }}"
                                    href="{{ route('banners.index') }}" id="add-list"> بنرها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('sliders.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('sliders.index') ? 'active1' : '' }}"
                                    href="{{ route('sliders.index') }}" id="add-list"> اسلایدرها </a>
                            </li>

                            {{-- <li class="nav-item {{ request()->routeIs('special-offers.edit') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('special-offers.edit') ? 'active1' : '' }}"
                                    href="{{ route('special-offers.edit') }}" id="add-list"> شگفت انگیز </a>
                            </li> --}}

                            {{-- <li class="nav-item {{ request()->routeIs('coupons.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('coupons.index') ? 'active1' : '' }}"
                                    href="{{ route('coupons.index') }}" id="add-list"> کدتخفیف </a>
                            </li> --}}


                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.show') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu5" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu5">
                        <i class="fa-solid fa-building-columns"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                        || request()->routeIs('reports.show') ? 'active' : '' }}"> مالی </span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.show') ? 'show' : '' }}" id="productMenu5"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            {{-- <li class="nav-item {{ request()->routeIs('orders.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('orders.index') ? 'active1' : '' }}"
                                    href="{{ route('orders.index') }}" id="show-list">  سفارش ها</a>
                            </li> --}}

                             <li class="nav-item {{ request()->routeIs('transaction.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('transaction.index') ? 'active1' : '' }}"
                                    href="{{ route('transaction.index') }}" id="add-list"> تراکنش ها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('reports.show') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('reports.show') ? 'active1' : '' }}"
                                    href="{{ route('reports.show', Auth::user()->organSelected) }}" id="add-list">  گزارش مالی </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('wallet.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('wallet.index') ? 'active1' : '' }}"
                                    href="{{ route('wallet.index') }}" id="add-list">  شارژ کیف پول </a>
                            </li>

                            {{-- <li class="nav-item {{ request()->routeIs('reports.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('reports.index') ? 'active1' : '' }}"
                                    href="{{ route('reports.index') }}" id="add-list"> گزارش های مالی </a>
                            </li> --}}

                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                    || request()->routeIs('organ.list') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu6" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu6">
                        <i class="fas fa-users"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                        || request()->routeIs('organ.list') ? 'active' : '' }}"> کاربران</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                    || request()->routeIs('organ.list') ? 'show' : '' }}" id="productMenu6"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('') ? 'active1' : '' }}"
                                    href="#" id="show-list">   مشتری ها</a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('user.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('user.list') ? 'active1' : '' }}"
                                    href="{{ route('user.list') }}" id="show-list">   اپراتور ها</a>
                            </li>

                             {{-- <li class="nav-item {{ request()->routeIs('editSharing') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('editSharing') ? 'active1' : '' }}"
                                    href="#" id="add-list">  سطح و نقش </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('organ.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('organ.list') ? 'active1' : '' }}"
                                    href="{{ route('organ.list') }}" id="add-list">  سالن ها   </a>
                            </li> --}}


                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('request.list') || request()->routeIs('tickets.index') 
                    || request()->routeIs('comments.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu8" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu8">
                        <i class="fa-solid fa-sliders"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('request.list') 
                        || request()->routeIs('tickets.index') || request()->routeIs('comments.index') ? 'active' : '' }}"> مدیریت</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('request.list') || request()->routeIs('tickets.index') 
                    || request()->routeIs('comments.index') ? 'show' : '' }}" id="productMenu8"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('request.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('request.list') ? 'active1' : '' }}"
                                    href="{{ route('request.list') }}" id="show-list">   درخواست ها</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('tickets.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('tickets.index') ? 'active1' : '' }}"
                                    href="{{ route('tickets.index') }}" id="add-list">  تیکت ها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('comments.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('comments.index') ? 'active1' : '' }}"
                                    href="{{ route('comments.index') }}" id="add-list">  دیدگاه ها   </a>
                            </li>


                        </ul>
                    </div>
                </li>

            @elseif(Auth::user()->hasRole('admin'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/service*') || request()->routeIs('category.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu3" role="button"
                        aria-expanded="{{ request()->is('admin/service*') ? 'true' : 'false' }}" aria-controls="productMenu3">
                        <i class="fa-solid fa-boxes-stacked me-2"></i>
                        <span class="menu-label {{ request()->is('admin/service*') || request()->routeIs('category.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'active' : '' }}">تعاریف پایه</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/service*') || request()->routeIs('category.list') 
                        || request()->routeIs('contract-templates.index') || request()->routeIs('site-rules.edit') 
                        || request()->routeIs('educational-videos.index') ? 'show' : '' }}" id="productMenu3"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('category.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('category.list') ? 'active1' : '' }}"
                                    href="{{ route('category.list') }}" id="show-list">  دسته بندی ها</a>
                            </li>
                             <li class="nav-item {{ request()->routeIs('contract-templates.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('contract-templates.index') ? 'active1' : '' }}"
                                    href="{{ route('contract-templates.index') }}" id="add-list">قرارداد ها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('site-rules.edit') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('site-rules.edit') ? 'active1' : '' }}"
                                    href="{{ route('site-rules.edit') }}" id="add-list"> قوانین </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('educational-videos.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('educational-videos.index') ? 'active1' : '' }}"
                                    href="{{ route('educational-videos.index') }}" id="add-list"> دوره های آموزشی </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') || request()->routeIs('coupons.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu2" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu2">
                        <i class="fa-solid fa-ticket"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') 
                    || request()->routeIs('coupons.index') ? 'active' : '' }}"">ماژول ها</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('galleries.index') || request()->routeIs('banners.index') 
                    || request()->routeIs('sliders.index') || request()->routeIs('special-offers.edit') 
                    || request()->routeIs('coupons.index') ? 'show' : '' }}" id="productMenu2"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('galleries.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('galleries.index') ? 'active1' : '' }}"
                                    href="{{ route('galleries.index') }}" id="show-list">  گالری عکس و فیلم</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('banners.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('banners.index') ? 'active1' : '' }}"
                                    href="{{ route('banners.index') }}" id="add-list"> بنرها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('sliders.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('sliders.index') ? 'active1' : '' }}"
                                    href="{{ route('sliders.index') }}" id="add-list"> اسلایدرها </a>
                            </li>

                            {{-- <li class="nav-item {{ request()->routeIs('special-offers.edit') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('special-offers.edit') ? 'active1' : '' }}"
                                    href="{{ route('special-offers.edit') }}" id="add-list"> شگفت انگیز </a>
                            </li> --}}

                            {{-- <li class="nav-item {{ request()->routeIs('coupons.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('coupons.index') ? 'active1' : '' }}"
                                    href="{{ route('coupons.index') }}" id="add-list"> کدتخفیف </a>
                            </li> --}}


                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('orders.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu5" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu5">
                        <i class="fa-solid fa-building-columns"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('orders.index') || request()->routeIs('transaction.index') 
                        || request()->routeIs('reports.index') ? 'active' : '' }}"> فروش ها</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('orders.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.index') ? 'show' : '' }}" id="productMenu5"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('orders.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('orders.index') ? 'active1' : '' }}"
                                    href="{{ route('orders.index') }}" id="show-list">  سفارش ها</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('transaction.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('transaction.index') ? 'active1' : '' }}"
                                    href="{{ route('transaction.index') }}" id="add-list"> تراکنش ها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('reports.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('reports.index') ? 'active1' : '' }}"
                                    href="{{ route('reports.index') }}" id="add-list"> گزارش های مالی </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                    || request()->routeIs('organ.list') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu6" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu6">
                        <i class="fas fa-users"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                        || request()->routeIs('organ.list') ? 'active' : '' }}"> کاربران</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('user.list') || request()->routeIs('editSharing') 
                    || request()->routeIs('organ.list') ? 'show' : '' }}" id="productMenu6"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('user.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('user.list') ? 'active1' : '' }}"
                                    href="{{ route('user.list') }}" id="show-list">   کاربران</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('editSharing') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('editSharing') ? 'active1' : '' }}"
                                    href="#" id="add-list">  سطح و نقش </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('organ.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('organ.list') ? 'active1' : '' }}"
                                    href="{{ route('organ.list') }}" id="add-list">  سالن ها   </a>
                            </li>


                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('request.list') || request()->routeIs('tickets.index') 
                    || request()->routeIs('comments.index') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu8" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu8">
                        <i class="fa-solid fa-sliders"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('request.list') 
                        || request()->routeIs('tickets.index') || request()->routeIs('comments.index') ? 'active' : '' }}"> مدیریت</span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('request.list') || request()->routeIs('tickets.index') 
                    || request()->routeIs('comments.index') ? 'show' : '' }}" id="productMenu8"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            <li class="nav-item {{ request()->routeIs('request.list') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('request.list') ? 'active1' : '' }}"
                                    href="{{ route('request.list') }}" id="show-list">   درخواست ها</a>
                            </li>

                             <li class="nav-item {{ request()->routeIs('tickets.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('tickets.index') ? 'active1' : '' }}"
                                    href="{{ route('tickets.index') }}" id="add-list">  تیکت ها </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('comments.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('comments.index') ? 'active1' : '' }}"
                                    href="{{ route('comments.index') }}" id="add-list">  دیدگاه ها   </a>
                            </li>


                        </ul>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('work-hour.index') ? 'active' : '' }}" href="{{ route('work-hour.index') }}">
                        <i class="fa-solid fa-clock"></i>
                        <span class="menu-label {{ request()->routeIs('work-hour.index') ? 'active' : '' }}"> ساعت کاری من   </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calende.operator') ? 'active' : '' }}" href="{{ route('calende.operator', Auth::user()) }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="menu-label {{ request()->routeIs('calende.operator') ? 'active' : '' }}">تقویم کاری    </span>
                    </a>
                </li>

                            <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="fa-solid fa-shopping-bag"></i>
                        <span class="menu-label {{ request()->routeIs('orders.index') ? 'active' : '' }}">سفارشات من    </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('Myservices.list') ? 'active' : '' }}" href="{{ route('Myservices.list') }}">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        <span class="menu-label {{ request()->routeIs('Myservices.list') ? 'active' : '' }}"> خدمات من   </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('galleries.index') ? 'active' : '' }}" href="{{ route('galleries.index') }}">
                        <i class="fa-solid fa-images"></i>
                        <span class="menu-label {{ request()->routeIs('galleries.index') ? 'active' : '' }}">  گالری عکس و فیلم   </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.show') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productMenu5" role="button"
                        aria-expanded="{{ request()->is('admin/sharing*') ? 'true' : 'false' }}" aria-controls="productMenu5">
                        <i class="fa-solid fa-building-columns"></i>
                        <span class="menu-label {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                        || request()->routeIs('reports.show') ? 'active' : '' }}"> مالی </span>
                        <!-- آیکون فلش -->
                        <i style="font-size:0.9rem" class="fa-solid fa-chevron-down ms-auto toggle-icon"></i>
                    </a>

                    <div class="collapse {{ request()->is('admin/sharing*') || request()->routeIs('wallet.index') || request()->routeIs('transaction.index') 
                    || request()->routeIs('reports.show') ? 'show' : '' }}" id="productMenu5"
                        style="border-right: 3px solid var(--text-main);margin-right: 1.6rem">
                        <ul class="nav flex-column ms-3 submenu" style="list-style-type: disc ; padding-right: 1.4rem">

                            {{-- <li class="nav-item {{ request()->routeIs('orders.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('orders.index') ? 'active1' : '' }}"
                                    href="{{ route('orders.index') }}" id="show-list">  سفارش ها</a>
                            </li> --}}

                             {{-- <li class="nav-item {{ request()->routeIs('transaction.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('transaction.index') ? 'active1' : '' }}"
                                    href="{{ route('transaction.index') }}" id="add-list"> تراکنش ها </a>
                            </li> --}}

                            <li class="nav-item {{ request()->routeIs('transaction.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('transaction.index') ? 'active1' : '' }}"
                                    href="{{ route('transaction.index') }}" id="add-list">  گزارش مالی </a>
                            </li>

                            <li class="nav-item {{ request()->routeIs('wallet.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('wallet.index') ? 'active1' : '' }}"
                                    href="{{ route('wallet.index') }}" id="add-list">  شارژ کیف پول </a>
                            </li>

                            {{-- <li class="nav-item {{ request()->routeIs('reports.index') ? 'activeLi' : '' }}">
                                <a class="nav-link {{ request()->routeIs('reports.index') ? 'active1' : '' }}"
                                    href="{{ route('reports.index') }}" id="add-list"> گزارش های مالی </a>
                            </li> --}}

                        </ul>
                    </div>
                </li>
            @endif
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user') ? 'active' : '' }}" href="#">
                    <i class="fas fa-users"></i>
                    <span class="menu-label {{ request()->routeIs('users') ? 'active' : '' }}">کاربران<span style="font-size: 0.6rem;">(بزودی)</span></span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('setting') ? 'active' : '' }}" href="/admin/setting">
                    <i class="fa-solid fa-sliders"></i>
                    <span class="menu-label {{ request()->routeIs('setting') ? 'active' : '' }}">تنظیمات</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('') ? 'active' : '' }}" href="/">
                    <i class="fa-solid fa-reply-all"></i>
                    <span class="menu-label">بازگشت به سایت</span>
                </a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('') ? 'active' : '' }}" href="/">
                    <i class="fa-solid fa-reply-all"></i>
                    <span class="menu-label">بازگشت به سایت  </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('') ? 'active' : '' }}" href="/logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="menu-label"> خروج از حساب کاربری</span>
                </a>
            </li>

        </ul>
    </div>