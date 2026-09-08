@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/profile.css') }}">
@endsection
@section('content')
    <div class="container p-0" style="max-width: 28rem;">
        <!-- هدر پروفایل -->
        <div class="profile-header">
            <button class="edit-profile-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil"></i>
            </button>

            <img src="{{ asset('files/no-image.png') }}" class="profile-avatar" alt="پروفایل کاربر">

            <div class="profile-name">{{ Auth::user()->name }}</div>
            <div class="profile-phone">{{ Auth::user()->mobile }}</div>
        </div>

        <!-- محتوای پروفایل -->
        <div class="profile-content">
            <!-- منوی پروفایل -->
            <div class="profile-menu fade-in">
                <div class="menu-item" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <div class="menu-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="menu-text">ویرایش پروفایل</div>
                    <div class="menu-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                </div>

                <div class="menu-item" data-bs-toggle="modal" data-bs-target="#cooperateModal">
                    <div class="menu-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <a href="/cooperation" class="menu-text">درخواست همکاری </a>
                    <div class="menu-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                </div>

                <div class="menu-item" data-bs-toggle="modal" data-bs-target="#appointmentsModal">
                    <div class="menu-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="menu-text">نوبت‌های من</div>
                    <div class="menu-badge">{{ Auth::user()->reservations()->count() }}</div>
                    <div class="menu-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                </div>

                <div class="menu-item" data-bs-toggle="modal" data-bs-target="#walletModal">
                    <div class="menu-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="menu-text">کیف پول</div>
                    <div class="menu-badge">{{ number_format(Auth::user()->wallet) }} تومان</div>
                    <div class="menu-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                </div>

                <div class="menu-item" data-bs-toggle="modal" data-bs-target="#favoritesModal">
                    <div class="menu-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="menu-text">علاقه‌مندی‌ها</div>
                    <div class="menu-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                </div>
            </div>

            <!-- بخش آخرین نوبت‌های من -->
            <div class="last-appointments fade-in">
                <div class="section-title">
                    <i class="bi bi-clock-history"></i>
                    آخرین نوبت‌های من
                </div>
                <div class="section-content">
                    @foreach (Auth::user()->reservations()->where('status', 'paid')->take(3)->get() as $reservation)
                        <div class="appointment-card">
                            <div class="appointment-info">
                                <h5>{{ $reservation->operator->name }}</h5>
                                <p>{{ $reservation->services()->first()->service->name }}</p>
                            </div>
                            <div class="appointment-date">
                                <div>{{ Jdate($reservation->start_at)->format('%B %d، %Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- سوالات متداول -->
            <div class="faq-section fade-in">
                <div class="section-title">
                    <i class="bi bi-question-circle"></i>
                    سوالات متداول
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        چگونه نوبت رزرو کنم؟
                        <i class="bi bi-chevron-down faq-arrow"></i>
                    </div>
                    <div class="faq-answer">
                        برای رزرو نوبت، به صفحه خدمات مورد نظر رفته و دکمه "رزرو نوبت" را انتخاب کنید. سپس تاریخ و زمان
                        مورد نظر خود را انتخاب نمایید.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        چگونه نوبت خود را لغو کنم؟
                        <i class="bi bi-chevron-down faq-arrow"></i>
                    </div>
                    <div class="faq-answer">
                        برای لغو نوبت، به بخش "نوبت‌های من" رفته و نوبت مورد نظر را انتخاب کنید. سپس گزینه "لغو نوبت" را
                        انتخاب نمایید.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        چگونه کیف پول خود را شارژ کنم؟
                        <i class="bi bi-chevron-down faq-arrow"></i>
                    </div>
                    <div class="faq-answer">
                        برای شارژ کیف پول، به بخش "کیف پول" رفته و مبلغ مورد نظر را انتخاب کنید. سپس با درگاه پرداخت
                        امن، عملیات شارژ را انجام دهید.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        چگونه با پشتیبانی تماس بگیرم؟
                        <i class="bi bi-chevron-down faq-arrow"></i>
                    </div>
                    <div class="faq-answer">
                        برای تماس با پشتیبانی، می‌توانید از طریق شماره ۰۲۱-۱۲۳۴۵۶۷۸ در ساعات اداری تماس حاصل فرمایید یا
                        از طریق چت آنلاین در اپلیکیشن با ما در ارتباط باشید.
                    </div>
                </div>
            </div>

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('operator'))
                <div class="logout-section fade-in">
                    <a href="{{ route('index') }}" class="panel-btn">
                        <i class="fa-solid fa-layer-group"></i>
                         ورود به پنل کاربری
                    </a>
                </div>
            @endif

            <!-- دکمه خروج -->
            <div class="logout-section fade-in">
                <a href="{{ route('logout') }}" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i>
                    خروج از حساب کاربری
                </a>
            </div>

            <!-- فاصله برای ناوبری پایین -->
            <div style="height: 80px;"></div>
        </div>
    </div>

    {{-- ⬇️ محاسبه استان/شهر فعلی کاربر برای پیش‌نمایش توی فرم ویرایش --}}
    @php
        $userProvinceId = Auth::user()->city;   // ⬅️ مستقیماً استان ذخیره‌شده
        $userCityId     = Auth::user()->city2;  // ⬅️ مستقیماً شهر ذخیره‌شده
    @endphp

    <!-- مودال ویرایش پروفایل -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">تکمیل پروفایل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="fullName" class="form-label">نام و نام خانوادگی</label>
                            <input type="text" class="form-control" name="name" id="fullName"
                                value="{{ Auth::user()->name }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">شماره موبایل</label>
                            <input type="tel" class="form-control" name="mobile" id="phone"
                                value="{{ Auth::user()->mobile }}">
                            @error('mobile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="birthDate" class="form-label">تاریخ تولد</label>
                            <input type="text" class="form-control" name="birthDate" id="birthDate"
                                placeholder="1404/01/11" value="{{ Auth::user()->birthDate }}">
                            @error('birthDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="provinceSelect" class="form-label">استان محل سکونت</label>
                            <select name="city" id="provinceSelect" class="form-select">
                                <option value="">انتخاب استان</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ $userProvinceId == $city->id ? 'selected' : '' }}>
                                        {{ $city->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="citySelect" class="form-label">شهر محل سکونت</label>
                            <select id="citySelect" class="form-select" name="city2"
                                {{ $userProvinceId ? '' : 'disabled' }}>
                                <option value="">ابتدا استان را انتخاب کنید</option>
                            </select>
                            @error('city_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">ذخیره تغییرات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال نوبت‌های من -->
    <div class="modal fade" id="appointmentsModal" tabindex="-1" aria-labelledby="appointmentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentsModalLabel">نوبت‌های من</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>تاریخ و زمان نوبت</th>
                                    <th>آرایشگر</th>
                                    <th>خدمت</th>
                                    <th>وضعیت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()->reservations()->get() as $reservation)
                                    <tr>
                                        <td>{{ Jdate($reservation->start_at)->format('%B %d، %Y H:i') }}</td>
                                        <td>{{ $reservation->operator->name }}</td>
                                        <td>{{ $reservation->services()->first()->service->name }}</td>
                                        <td>
                                            @switch($reservation->status)
                                                @case('pending')
                                                    <span class="status-badge status-pending">در انتظار پرداخت</span>
                                                @break

                                                @case('paid')
                                                    <span class="status-badge status-confirmed">تأیید شده</span>
                                                @break

                                                @case('canceled')
                                                    <span class="status-badge status-canceled">کنسل شده</span>
                                                @break

                                                @case('done')
                                                    <span class="status-badge status-confirmed">تکمیل شده</span>
                                                @break

                                                @default
                                                    <span class="status-badge status-pending">لغو سیستمی</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال کیف پول -->
    <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="walletModalLabel">کیف پول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h4>موجودی فعلی</h4>
                        <h3 class="text-primary">۱۵۰,۰۰۰ تومان</h3>
                    </div>

                    <h6 class="mb-3">انتخاب مبلغ برای شارژ:</h6>
                    <div class="wallet-amounts">
                        <div class="wallet-amount" data-amount="50000">
                            <div class="wallet-amount-price">۵۰,۰۰۰ تومان</div>
                            <div class="wallet-amount-bonus">+۵,۰۰۰ هدیه</div>
                        </div>
                        <div class="wallet-amount active" data-amount="100000">
                            <div class="wallet-amount-price">۱۰۰,۰۰۰ تومان</div>
                            <div class="wallet-amount-bonus">+۱۵,۰۰۰ هدیه</div>
                        </div>
                        <div class="wallet-amount" data-amount="200000">
                            <div class="wallet-amount-price">۲۰۰,۰۰۰ تومان</div>
                            <div class="wallet-amount-bonus">+۳۰,۰۰۰ هدیه</div>
                        </div>
                        <div class="wallet-amount" data-amount="500000">
                            <div class="wallet-amount-price">۵۰۰,۰۰۰ تومان</div>
                            <div class="wallet-amount-bonus">+۷۵,۰۰۰ هدیه</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customAmount" class="form-label">مبلغ دلخواه</label>
                        <input type="text" class="form-control" id="customAmount" placeholder="مبلغ به تومان">
                    </div>

                    <button class="btn btn-primary w-100">شارژ کیف پول</button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال علاقه‌مندی‌ها -->
    <div class="modal fade" id="favoritesModal" tabindex="-1" aria-labelledby="favoritesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="favoritesModalLabel">علاقه‌مندی‌ها</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach (Auth::user()->favorites as $service)
                            <div class="organ-service-card d-flex">
                                <a href="{{ route('service', ['service' => $service]) }}"
                                    class="text-reset text-decoration-none" style="width: 40%;">
                                    <img src="{{ asset($service->image ?? 'files/no-image.png') }}" class="service-image"
                                        alt="{{ $service->name }}">
                                </a>
                                <div class="service-content">
                                    {{-- like --}}
                                    <button
                                        class="like-btn {{ Auth::user()->favorites()->where('service_id', $service->id)->first() ? 'active' : '' }}"
                                        data-service-id="{{ $service->id }}">
                                        <i
                                            class="bi {{ Auth::user()->favorites()->where('service_id', $service->id)->first() ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    </button>

                                    <a href="{{ route('service', ['service' => $service]) }}"
                                        class="text-reset text-decoration-none">
                                        <h6 class="service-title mb-3">{{ $service->name }}</h6>
                                    </a>
                                    <span class="discount-price">
                                        {{ $service->price }}
                                        {{ $service->price_max > 0 ? ' تا ' . $service->price_max : '' }}
                                        تومان</span>
                                    <div class="service-features">
                                        <span class="feature-badge available"><i class="bi bi-clock me-1"></i>
                                            نوبت امروز</span>
                                        <span class="feature-badge"><i class="bi bi-person me-1"></i> الهام
                                            احمدی</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال گالری تصاویر -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">گالری تصاویر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="gallery-grid mb-4">
                        <div class="gallery-item">
                            <img src="{{ asset('files/no-image.png') }}" alt="عکس ۱">
                        </div>
                        <div class="gallery-item">
                            <img src="{{ asset('files/no-image.png') }}" alt="عکس ۲">
                        </div>
                        <div class="gallery-item">
                            <img src="{{ asset('files/no-image.png') }}" alt="عکس ۳">
                        </div>
                        <div class="gallery-item">
                            <img src="{{ asset('files/no-image.png') }}" alt="عکس ۴">
                        </div>
                        <div class="gallery-item">
                            <img src="{{ asset('files/no-image.png') }}" alt="عکس ۵">
                        </div>
                        <div class="gallery-item add-photo-btn" id="addPhotoBtn">
                            <i class="bi bi-plus-lg" style="font-size: 24px;"></i>
                            <span style="font-size: 12px; margin-top: 5px;">افزودن عکس</span>
                        </div>
                    </div>
                    <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- ⬇️ همه‌ی اسکریپت‌ها یکجا، توی یک @section('scripts') --}}
@section('scripts')
    <script>
        // مقادیر اولیه‌ی استان/شهر کاربر (برای پیش‌نمایش در فرم ویرایش)
        const initialProvinceId = @json($userProvinceId);
        const initialCityId = @json($userCityId);

        function loadCities(provinceId, selectedCityId = null) {
            const citySelect = $('#citySelect');
            citySelect.empty();

            if (!provinceId) {
                citySelect.append('<option value="">ابتدا استان را انتخاب کنید</option>');
                citySelect.prop('disabled', true);
                return;
            }

            citySelect.prop('disabled', true);
            citySelect.append('<option value="">در حال بارگذاری...</option>');

            $.ajax({
                url: '/api/cities/by-province/' + provinceId,
                method: 'GET',
                success: function (response) {
                    citySelect.empty();
                    citySelect.append('<option value="">انتخاب شهر</option>');

                    response.data.forEach(function (city) {
                        const isSelected = (selectedCityId && city.id == selectedCityId) ? 'selected' : '';
                        citySelect.append(`<option value="${city.id}" ${isSelected}>${city.title}</option>`);
                    });

                    citySelect.prop('disabled', false);
                },
                error: function () {
                    citySelect.empty();
                    citySelect.append('<option value="">خطا در دریافت شهرها</option>');
                }
            });
        }

        // وقتی کاربر دستی استان رو عوض کنه
        $(document).on('change', '#provinceSelect', function () {
            loadCities($(this).val());
        });

        // موقع لود صفحه، اگه مقدار قبلی وجود داشت، خودکار پر کن
        $(document).ready(function () {
            if (initialProvinceId) {
                loadCities(initialProvinceId, initialCityId);
            }
        });

        // فرمت کردن مبلغ کیف پول
        const amountInput = document.getElementById('customAmount');
        if (amountInput) {
            amountInput.addEventListener('input', function (e) {
                // تبدیل اعداد فارسی به انگلیسی
                let value = e.target.value.replace(/[۰-۹]/g, function (d) {
                    return String.fromCharCode(d.charCodeAt(0) - 1728);
                });

                // حذف هر چیزی غیر از عدد
                value = value.replace(/\D/g, '');

                // جدا کردن سه رقم سه رقم
                if (value) {
                    value = Number(value).toLocaleString('en-US');
                }

                e.target.value = value;
            });
        }

        $(document).ready(function() {
            // مدیریت باز و بسته شدن سوالات متداول
            $('.faq-question').click(function() {
                $(this).closest('.faq-item').toggleClass('active');
                $(this).closest('.faq-item').siblings().removeClass('active');
            });

            // مدیریت انتخاب مبلغ در کیف پول
            $('.wallet-amount').click(function() {
                $('.wallet-amount').removeClass('active');
                $(this).addClass('active');
            });

            // مدیریت آپلود عکس
            $('#addPhotoBtn').click(function() {
                $('#photoUpload').click();
            });

            $('#photoUpload').change(function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // ایجاد المان جدید برای عکس آپلود شده
                        const newPhoto = $('<div class="gallery-item"><img src="' + e.target.result +
                            '" alt="عکس جدید"></div>');

                        // جایگزینی دکمه افزودن با عکس جدید
                        $('#addPhotoBtn').before(newPhoto);
                    }

                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    </script>
@endsection