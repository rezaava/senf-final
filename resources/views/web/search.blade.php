@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/date.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/map.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="container py-3 position-relative" style="max-width: 28rem;min-height: 100dvh;">
        <!-- search container -->
        <div class="search-container mb-3">
            <div class="tabs pt-3">
                <div class="tab d-flex flex-column gap-2" data-tab="map">
                    <i class="fas fa-map"></i>
                    نقشه
                </div>
                <div class="tab d-flex flex-column gap-2" data-tab="salons">
                    <i class="fas fa-shop"></i>
                    آرایشگاه‌ها
                </div>
                <div class="tab d-flex flex-column gap-2 active" data-tab="services">
                    <i class="fas fa-scissors"></i>
                    خدمات
                </div>
            </div>

            <div class="tab-content">
                <!-- تب نقشه -->
                <div class="tab-pane" id="map">
                    <!-- نقشه -->
                    <div id="map" style="min-height: 400px">
                        <!-- کنترل‌های نقشه -->
                        <div class="map-controls">
                            <button id="locateBtn" class="btn-map-control" title="موقعیت من">
                                <i class="fas fa-location-arrow"></i>
                            </button>
                            <button id="zoomInBtn" class="btn-map-control" title="بزرگنمایی">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button id="zoomOutBtn" class="btn-map-control" title="کوچکنمایی">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- تب آرایشگاه‌ها -->
                <div class="tab-pane" id="salons">
                    <div class="input-group">
                        <input type="text" class="input-field" id="salon-search" placeholder=" ">
                        <label class="input-label" for="salon-search">جستجوی نام آرایشگاه</label>
                        <i class="input-icon fas fa-search"></i>
                        <button class="clear-btn">&times;</button>
                    </div>
                    <button class="main-btn btn" id="search-salons-btn">جستجوی آرایشگاه</button>
                </div>

                <!-- تب خدمات -->
                <div class="tab-pane active" id="services">
                    <div class="input-group">
                        <input type="text" class="input-field" id="service-category" placeholder=" " autocomplete="off">
                        <label class="input-label" for="service-category">دسته‌بندی خدمات</label>
                        <i class="input-icon fas fa-search"></i>
                        <button class="clear-btn">&times;</button>
                        <div class="dropdown-list" id="category-list">
                            @foreach ($categories as $category)
                                <div class="dropdown-item" data-id="{{ $category->id }}">{{ $category->name }}</div>
                            @endforeach
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" class="input-field datepicker-input" id="service-date" placeholder=" "
                            autocomplete="off">
                        <label class="input-label" for="service-date">تاریخ</label>
                        <i class="input-icon fas fa-calendar-alt"></i>
                        <button class="clear-btn">&times;</button>
                    </div>

                    <button class="main-btn btn" id="search-services-btn">جستجوی خدمات</button>
                </div>
            </div>
        </div>

        <div class="container px-1">
            <!-- مرتب‌سازی و تعداد نتایج -->
            <div class="row">
                <div class="col-md-12">
                    {{-- <div class="results-count">
                        <span id="results-count">0</span> نتیجه یافت شد
                    </div> --}}
                </div>
                <div class="col-md-12">
                    <div class="sort-options">
                        <div class="me-2">مرتب‌سازی بر اساس:</div>
                        <span class="sort-btn active" data-sort="rating">بالاترین امتیاز</span>
                        <span class="sort-btn" data-sort="cheapest">ارزانترین</span>
                        <span class="sort-btn" data-sort="expensive">گرانترین</span>
                        <span class="sort-btn" data-sort="nearest">نزدیکترین</span>
                    </div>
                </div>
            </div>

            <!-- لیست نتایج -->
            <div class="row" id="results-container">
                <!-- نتایج توسط JavaScript پر می‌شوند -->
            </div>

            <!-- صفحه‌بندی -->
            <div id="pagination-container" class="mt-3"></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="{{ asset('asset/js/persian-datepicker.min.js') }}"></script>
    <script src="{{ asset('asset/js/leaflet.js') }}"></script>
    <script>
        $(document).ready(function() {
            // تنظیم CSRF Token برای درخواست‌های AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            // خواندن پارامترهای URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const nameParam = urlParams.get('name');
            const categoryParam = urlParams.get('category');
            const dateParam = urlParams.get('date');

            let currentSort = 'rating';
            let currentPage = 1;
            let currentTab = 'services';
            // فلگ برای جلوگیری از اجرای دوباره‌ی جستجوی سرویس‌ها در لود اولیه
            let initialServiceSearchDone = false;
            // فلگ برای لود تنبل (lazy) نقشه، فقط وقتی کاربر روی تب نقشه کلیک کرد
            let mapInitialized = false;

            if (tabParam) {

                currentTab = tabParam || 'services';

                $(".tab").removeClass("active");
                $(`.tab[data-tab="${tabParam}"]`).addClass("active");

                $(".tab-pane").removeClass("active");
                $(`#${tabParam}`).addClass("active");
            }
            if (tabParam === 'salons' && nameParam) {
                $('#salon-search').val(nameParam);
                $('#salon-search').siblings('.input-label').addClass('active');
            }
            if (tabParam === 'services') {

                if (dateParam) {
                    $('#service-date').val(dateParam);
                    $('#service-date').siblings('.input-label').addClass('active');
                }

                if (categoryParam) {
                    // بعد از لود دسته‌بندی‌ها اجرا بشه
                    const item =
                        $(`#category-list .dropdown-item[data-id="${categoryParam}"]`);

                    if (item.length) {
                        // item.addClass('selected');
                        // $('#service-category').val(item.text());
                        // $('#service-category').siblings('.input-label').addClass('active');
                        item.click()
                    }
                }
            }
            if (tabParam === 'salons') {
                searchSalons();
            }

            // جستجوی اولیه سرویس‌ها: چه tab=services باشه چه اصلا پارامتری نباشه (تب پیش‌فرض)
            // این جایگزینِ فراخوانی تکراریِ searchServices() در انتهای فایل شده تا
            // دو درخواست AJAX هم‌زمان به سرور ارسال نشه و لود صفحه سریع‌تر بشه
            if (!tabParam || tabParam === 'services') {
                searchServices();
                initialServiceSearchDone = true;
            }

            if (tabParam === 'map') {
                initMap();
                setupMapControls();
                mapInitialized = true;
            }

            // لود دسته‌بندی‌ها و شهرها
            loadCategories();

            // مدیریت تب‌ها
            $(".tab").on("click", function() {
                const tabId = $(this).data("tab");
                currentTab = tabId;

                $(".tab").removeClass("active");
                $(this).addClass("active");

                $(".tab-pane").removeClass("active");
                $(`#${tabId}`).addClass("active");

                // لود تنبل نقشه: فقط اولین باری که کاربر وارد تب نقشه می‌شه ساخته می‌شه
                if (tabId === 'map' && !mapInitialized) {
                    initMap();
                    setupMapControls();
                    mapInitialized = true;
                }

                // پاک کردن نتایج قبلی
                clearResults();
            });

            // لود دسته‌بندی‌ها
            function loadCategories() {
                $.ajax({
                    url: "{{ route('api.search.categories') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const categoryList = $('#category-list');
                            categoryList.empty();

                            response.categories.forEach(function(category) {
                                categoryList.append(`
                                <div class="dropdown-item" data-id="${category.id}">${category.name}</div>
                            `);
                            });
                        }
                    },
                    error: function() {
                        console.error('خطا در دریافت دسته‌بندی‌ها');
                    }
                });
            }

            // لود شهرها


            // جستجوی آرایشگاه‌ها
            $('#search-salons-btn').on('click', function() {
                searchSalons();
            });

            // جستجوی خدمات
            $('#search-services-btn').on('click', function() {
                searchServices();
            });

            function searchSalons(page = 1) {
                const searchData = {
                    name: $('#salon-search').val(),
                    sort_by: currentSort,
                    page: page
                };

                $.ajax({
                    url: "{{ route('api.search.salons') }}",
                    method: 'POST',
                    data: searchData,
                    success: function(response) {
                        if (response.success) {
                            renderSalons(response.salons.data);
                            $('#results-count').text(response.salons.total);
                            renderPagination(response.salons);
                        }
                    },
                    error: function(xhr) {
                        console.error('خطا در جستجوی آرایشگاه‌ها', xhr.responseText);
                        showError('خطا در جستجوی آرایشگاه‌ها');
                    }
                });
            }

            function searchServices(page = 1) {
                const searchData = {
                    category_id: getSelectedCategoryId(),
                    date: $('#service-date').val(),
                    sort_by: currentSort,
                    page: page
                };

                $.ajax({
                    url: "{{ route('api.search.services') }}",
                    method: 'POST',
                    data: searchData,
                    success: function(response) {
                        if (response.success) {
                            renderServices(response.services.data);
                            $('#results-count').text(response.services.total);
                            renderPagination(response.services);
                        }
                    },
                    error: function(xhr) {
                        console.error('خطا در جستجوی خدمات', xhr.responseText);
                        showError('خطا در جستجوی خدمات');
                    }
                });
            }

            function getSelectedCategoryId() {
                const selectedItem = $('#category-list .dropdown-item.selected');
                return selectedItem.length ? selectedItem.data('id') : null;
            }


            function renderSalons(salons) {
                const container = $("#results-container");
                container.empty();

                if (salons.length === 0) {
                    container.append(`
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">نتیجه‌ای یافت نشد</h4>
                        <p class="text-muted">لطفاً فیلترهای جستجو را تغییر دهید</p>
                    </div>
                `);
                    return;
                }

                salons.forEach(salon => {
                    const servicesHtml = salon.services ? salon.services.map(service => `
                    <a href="/service/${service.id}" class="text-decoration-none text-reset">
                        <div class="service-item">
                            <span>${service.name}</span>
                            <span class="service-price">${service.price.toLocaleString()} تومان</span>
                        </div>
                    </a>
                `).join('') : '';

                    const ratingStars = '★'.repeat(Math.floor(4.3)) +
                        '☆'.repeat(5 - Math.floor(4.3));

                    const card = `
                    <div class="col-md-12 mb-3">
                        <div class="card-custom shadow bg-white">
                            <a href="/salon/${salon.id}" class="text-decoration-none text-reset">
                                <div class="d-flex justify-content-start align-items-start gap-3">
                                    <img src="${salon.image || '{{ asset('files/no-image.png') }}'}"
                                         alt="${salon.name}"
                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="fw-bold">${salon.name}</h5>
                                                <div class="address mb-1">
                                                    <i class="bi bi-geo-alt"></i> ${salon.address}
                                                </div>
                                                <div class="rating">
                                                    ${ratingStars} <span class="text-muted">(4.3)</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">2+ نظر</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            ${servicesHtml ? `
                                                                    <div class="service-list mt-3">
                                                                        <h6 class="mb-2">خدمات:</h6>
                                                                        ${servicesHtml}
                                                                    </div>` : ''}
                        </div>
                    </div>
                `;

                    container.append(card);
                });
            }

            function renderServices(services) {
                const container = $("#results-container");
                container.empty();

                if (services.length === 0) {
                    container.append(`
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">نتیجه‌ای یافت نشد</h4>
                        <p class="text-muted">لطفاً فیلترهای جستجو را تغییر دهید</p>
                    </div>
                `);
                    return;
                }

                services.forEach(service => {
                    const salon = service.organ;
                    const ratingStars = '★'.repeat(Math.floor(4.3)) +
                        '☆'.repeat(5 - Math.floor(4.3));

                    const card = `
                    <div class="col-md-12 mb-3">
                        <div class="card-custom shadow bg-white">
                            <a href="/service/${service.id}" class="text-decoration-none text-reset">
                                <div class="d-flex justify-content-start align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">${service.name}</h5>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary">${service.category.name}</span>
                                            <span class="service-price fw-bold">
                                                ${service.price.toLocaleString()} تومان
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="bi bi-clock"></i>
                                            مدت زمان: ${service.time} دقیقه
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <hr>
                            <div class="d-flex justify-content-start align-items-start gap-3">
                                <img src="${salon.image || '{{ asset('files/no-image.png') }}'}"
                                     alt="${salon.name}"
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div>
                                    <h6 class="fw-bold mb-1">${salon.name}</h6>
                                    <div class="address mb-1">
                                        <i class="bi bi-geo-alt"></i> ${salon.address}
                                    </div>
                                    <div class="rating">
                                        ${ratingStars} <span class="text-muted">(4.3)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                    container.append(card);
                });
            }

            function renderPagination(paginator) {
                const container = $('#pagination-container');
                container.empty();

                if (paginator.last_page > 1) {
                    let paginationHtml = '<nav><ul class="pagination justify-content-center">';

                    // دکمه قبلی
                    if (paginator.current_page > 1) {
                        paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${paginator.current_page - 1}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    `;
                    }

                    // صفحات
                    for (let i = 1; i <= paginator.last_page; i++) {
                        if (i === paginator.current_page) {
                            paginationHtml += `
                            <li class="page-item active">
                                <span class="page-link">${i}</span>
                            </li>
                        `;
                        } else {
                            paginationHtml += `
                            <li class="page-item">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>
                        `;
                        }
                    }

                    // دکمه بعدی
                    if (paginator.current_page < paginator.last_page) {
                        paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${paginator.current_page + 1}">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    `;
                    }

                    paginationHtml += '</ul></nav>';
                    container.html(paginationHtml);

                    // مدیریت کلیک روی صفحه‌بندی
                    container.on('click', '.page-link', function(e) {
                        e.preventDefault();
                        const page = $(this).data('page');

                        if (currentTab === 'salons') {
                            searchSalons(page);
                        } else if (currentTab === 'services') {
                            searchServices(page);
                        }
                    });
                }
            }

            function clearResults() {
                $('#results-container').empty();
                $('#pagination-container').empty();
                $('#results-count').text('0');
            }

            function showError(message) {
                const container = $("#results-container");
                container.empty();
                container.append(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                    <h4 class="mt-3 text-danger">خطا</h4>
                    <p class="text-muted">${message}</p>
                </div>
            `);
            }

            // مدیریت کلیک روی دکمه‌های مرتب‌سازی
            $(document).on("click", ".sort-btn", function() {
                $(".sort-btn").removeClass("active");
                $(this).addClass("active");

                currentSort = $(this).data("sort");

                // اجرای جستجو با مرتب‌سازی جدید
                if (currentTab === 'salons') {
                    searchSalons();
                } else if (currentTab === 'services') {
                    searchServices();
                }
            });

            // انتخاب از لیست کشویی
            $(document).on('click', '.dropdown-item', function() {
                const input = $(this).parent().siblings('.input-field');
                input.val($(this).text());
                $(this).addClass('selected').siblings().removeClass('selected');
                $(this).parent().hide();
                input.siblings('.input-label').addClass('active');
                input.siblings('.clear-btn').show();
            });

            // دکمه پاک‌کننده
            $('.clear-btn').on('click', function() {
                $(this).siblings('.input-field').val('').focus();
                $(this).siblings('.input-label').removeClass('active');
                $(this).hide();
                $('.dropdown-list').hide();
                $('.dropdown-item').removeClass('selected');
            });

            // مدیریت نمایش/پنهان کردن دکمه پاک‌کننده
            $('.input-field').on('input', function() {
                const clearBtn = $(this).siblings('.clear-btn');
                if ($(this).val()) {
                    clearBtn.show();
                } else {
                    clearBtn.hide();
                }
            });

            // تاریخ‌یاب فارسی
            $('#service-date').persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                initialValue: false,
                persianDigit: true
            });

            // نکته: فراخوانی تکراریِ searchServices() که قبلا اینجا بود حذف شد
            // چون بالاتر، در همان لود اولیه، یک‌بار (و فقط یک‌بار) اجرا می‌شود.

            // -------------------------------------------------------------
            // کد نقشه (initMap / setupMapControls) به همین اسکوپ منتقل شده
            // تا بتونه به متغیرهای بالا (mapInitialized و ...) دسترسی داشته باشه
            // و به‌صورت lazy فقط با کلیک روی تب «نقشه» اجرا بشه، نه همیشه.
            // -------------------------------------------------------------
            const salonsNew = []; // این داده‌ها باید از API گرفته شوند
            let map;
            let markers = [];
            let userLocationIcon; // آیکون نشانگر موقعیت کاربر (رفع باگ متغیر تعریف‌نشده)

            function initMap() {
                // مختصات مرکز تهران
                const tehranCoords = [35.6892, 51.3890];

                // ایجاد نقشه
                map = L.map('map').setView(tehranCoords, 12);

                // اضافه کردن لایه نقشه
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // آیکون سفارشی مشترک برای نشانگرها
                userLocationIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: var(--color-primary); width: 100%; height: 100%; border-radius: 50%;"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                // اضافه کردن نشانگر برای هر آرایشگاه
                salonsNew.forEach(salon => {
                    const marker = L.marker([salon.lat, salon.lng], {
                            icon: userLocationIcon
                        })
                        .addTo(map)
                        .bindPopup(`
                            <div style="text-align: right; font-family: Vazir, sans-serif;">
                                <h5 style="color: var(--color-primary); margin: 0 0 5px;">${salon.name}</h5>
                                <p style="margin: 0 0 5px; font-size: 0.9rem;">${salon.address}</p>
                                <div style="color: #FFC107; font-size: 0.9rem;">
                                    <i class="fas fa-star"></i> 4.3
                                </div>
                            </div>
                        `);

                    markers.push({
                        id: salon.id,
                        marker: marker
                    });
                });
            }

            function setupMapControls() {
                // بزرگنمایی
                document.getElementById('zoomInBtn').addEventListener('click', () => {
                    map.zoomIn();
                });

                // کوچکنمایی
                document.getElementById('zoomOutBtn').addEventListener('click', () => {
                    map.zoomOut();
                });

                // موقعیت من
                document.getElementById('locateBtn').addEventListener('click', () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            map.setView([userLat, userLng], 14);

                            // اضافه کردن نشانگر موقعیت کاربر
                            L.marker([userLat, userLng], {
                                    icon: userLocationIcon
                                })
                                .addTo(map)
                                .bindPopup('موقعیت شما')
                                .openPopup();
                        }, () => {
                            alert('دسترسی به موقعیت مکانی مجاز نیست.');
                        });
                    } else {
                        alert('مرورگر شما از موقعیت‌یابی پشتیبانی نمی‌کند.');
                    }
                });
            }
        });
    </script>
@endsection