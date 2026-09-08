@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="https://lib.arvancloud.ir/Swiper/9.0.5/swiper-bundle.css">
    <link rel="stylesheet" href="{{ asset('asset/css/service.css') }}">
    <style>
        .service-card {
            width: 80px;
        }

        .service-icon img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .splide__arrows {
            display: none;
        }

        .star {
            font-size: 26px;
            color: #ccc;
            cursor: pointer;
        }

        .star.active {
            color: #f5c518;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <!-- هدر خدمت -->
    <div class="service-header w-100 position-relative">
        @if ($service->image)
            <img src="{{ asset($service->image ?? 'files/no-image.png') }}" class="w-100 h-100 object-fit-cover"
                alt="{{ $service->name }}">
        @endif

        <div class="service-title">
            <h3 class="fw-bold">{{ $service->name }}</h3>
            <div class="d-flex align-items-center">
                <i class="bi bi-star-fill text-warning"></i>
                @php
                    $comments = $service->comments()->where('is_approved', true)->get();
                    $score = $comments->sum('score') / ($comments->count() > 0 ? $comments->count() : 1);
                @endphp
                <span class="ms-2">{{ $score }}</span> <small class="ms-2" style="font-size: 10px;">
                    ({{ number_format($comments->count()) }} نظر) </small>
            </div>
        </div>

        <div class="back-btn bg-white">
            <a href="{{ route('salon', ['salon' => $service->organ->id]) }}">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="like-div bg-white {{ Auth::user()->favorites()->where('service_id', $service->id)->first() ? 'active' : '' }}"
            data-service-id="{{ $service->id }}">
            <button
                class="like-btn {{ Auth::user()->favorites()->where('service_id', $service->id)->first() ? 'active' : '' }}"
                data-service-id="{{ $service->id }}">
                <i
                    class="bi {{ Auth::user()->favorites()->where('service_id', $service->id)->first() ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            </button>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-12 px-3">
            <!-- اطلاعات خدمت -->
            <div class="service-info-card shadow-sm border">
                <p>{{ $service->description }}</p>

                {{-- <h6 class="fw-bold mt-4">ویژگی های خدمت:</h6> --}}
                <ul class="feature-list">
                    <li><i class="bi bi-clock"></i> زمان تقریبی: {{ Jdate($service->time)->format('H:i') }}</li>
                    <li><i class="bi bi-check-circle"></i> مشاوره رایگان قبل از خدمت</li>
                </ul>

                <div class="price-section flex-column text-start">
                    {{-- <div class="mb-2 w-100">
                        <span class="original-price">120,000 تومان</span>
                        <span class="discount-badge">15% تخفیف</span>
                    </div> --}}
                    <div class="w-100">
                        <span class="discount-price">
                            {{ number_format($service->price) }}
                            {{ number_format    ($service->price_max) > 0 ? ' تا ' . number_format($service->price_max) : '' }}
                            تومان</span>
                    </div>
                </div>
                <button class="btn reserve-btn-service w-100 mt-4" data-bs-toggle="modal"
                    data-bs-target="#reservationModal">
                    <i class="bi bi-calendar-check ms-2"></i> رزرو نوبت
                </button>
            </div>
        </div>
    </div>

    @if ($service->gallery()->count() > 0)
        <div class="row g-0">
            <div class="col-12 px-3">
                <!-- گالری تصاویر -->
                <div class="gallery-section shadow-sm border">
                    <h6 class="fw-bold mb-3">گالری تصاویر</h6>

                    <div class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            @foreach ($service->gallery as $key => $item)
                                <div class="swiper-slide">
                                    <img src="{{ asset($item->path) }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $item->title }}" data-bs-toggle="modal" data-bs-target="#galleryModal">
                                </div>
                            @endforeach
                        </div>
                        <!-- اضافه کردن دکمه های ناوبری -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <!-- گالری کوچک -->
                    <div class="gallery-thumbs swiper">
                        <div class="swiper-wrapper">
                            @foreach ($service->gallery as $key => $item)
                                <div class="swiper-slide">
                                    <img src="{{ asset($item->path) }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $item->title }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-0">
        <div class="col-12 px-3">
            <!-- توضیحات کامل -->
            <!-- <div class="service-info-card mt-4">
                                                                                                        <h6 class="fw-bold">توضیحات کامل</h6>
                                                                                                        <p class="mt-3">
                                                                                                            در این خدمت، موهای شما توسط متخصصین آرایشگاه با توجه به فرم صورت و latest trends برش داده می شود.
                                                                                                            از بهترین محصولات مراقبت از مو استفاده شده و در پایان نکات لازم برای نگهداری و استایل دهی به شما آموزش
                                                                                                            داده می شود.
                                                                                                        </p>
                                                                                                        <p>
                                                                                                            این خدمت شامل شامپو، کاندیشن، برش حرفه ای، سشوار و استایل دهی می باشد.
                                                                                                            در صورت تمایل به رنگ یا هایلایت می توانید از خدمات تکمیلی ما استفاده کنید.
                                                                                                        </p>
                                                                                                    </div> -->
        </div>
    </div>

    <div class="row g-0 mb-5">
        <div class="col-12 px-3">
            <!-- نظرات -->
            <div class="service-info-card mt-4 shadow-sm border">
                <div class="section-title pb-3 d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="vertical-line rounded"
                            style="display: inline-block; width: 5px; height: 20px; background-color: var(--color-primary); margin-left: 10px;"></span>
                        <h6 class="title fw-bold d-inline m-0">دیدگاه کاربران</h6>
                    </div>
                    <div class="align-content-center">
                        <button class="btn btn-sm btn-main" data-bs-toggle="modal" data-bs-target="#myModal">ارسال
                            دیدگاه</button>
                    </div>
                </div>
                @foreach ($comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <img src="{{ asset($comment->user->image ?? 'files/no-image.png') }}" class="comment-avatar"
                                alt="{{ $comment->name }}">
                            <div class="w-100 d-flex justify-content-between">
                                <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                <div class="d-flex align-items-center">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $comment->score ?? 0)
                                            <i class="fa-solid fa-star text-warning small"></i>
                                        @else
                                            <i class="fa-regular fa-star text-warning small"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted small ms-2">{{ Jdate($comment->created_at)->ago() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">{{ $comment->text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- مودال نظر -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Content section -->
                    <div class="" style="direction: ltr;">
                        <div class="p-0 mb-4" dir="rtl">
                            <div class="mb-3 mt-3">
                                <div class="autocomplete" id="autocompleteBoxdescription">
                                    <textarea id="searchInputdescription" name="description" rows="4" oninput="nameinput('description')"></textarea>
                                    <label for="searchInputdescription" style="top: 10px;">متن
                                        دیدگاه</label>
                                    <span class="clear-btn" id="clearBtn_description" onclick="clearInput('description')"
                                        style="top: 23px;">×</span>
                                </div>
                            </div>
                            <!-- ⭐ Rating -->
                            <div class="mb-3 text-center" dir="ltr">
                                <div id="ratingStars" class="d-flex justify-content-center gap-1">
                                    <i class="fa fa-star star" data-value="1"></i>
                                    <i class="fa fa-star star" data-value="2"></i>
                                    <i class="fa fa-star star" data-value="3"></i>
                                    <i class="fa fa-star star" data-value="4"></i>
                                    <i class="fa fa-star star" data-value="5"></i>
                                </div>
                                <input type="hidden" id="ratingValue" value="0">
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="d-flex justify-content-start gap-3 align-items-center">
                            <button type="button" class="btn btn-primary d-flex align-items-center px-4 py-2"
                                id="submitComment">
                                ارسال دیدگاه
                            </button>

                            <button type="button" class="btn btn-outline-danger px-4 py-2" data-bs-dismiss="modal">
                                لغو
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- مودال گالری -->
    <div class="modal fade gallery-modal" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="swiper gallery-modal-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($service->gallery as $key => $item)
                                <div class="swiper-slide">
                                    <img src="{{ asset($item->path) }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $item->title }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- مودال رزرو خدمت -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">رزرو نوبت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- مرحله 1: انتخاب آرایشگر، تاریخ و زمان -->
                    <div class="step-content" id="step1">
                        <h6 class="text-center mb-4">آرایشگر، تاریخ و زمان مورد نظر خود را انتخاب کنید</h6>
                        <div class="scrollable-services mb-4 mt-2">
                            <div class="splide" id="categories" role="group" aria-label="Splide Basic HTML Example">
                                <div class="splide__track py-3">
                                    <ul class="splide__list py-3" id="stylistsList">
                                        <!-- آرایشگرها از طریق JavaScript پر می‌شوند -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="stylistsLoader" class="text-center my-3 d-none">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                            <p class="mt-2 text-muted">در حال بارگذاری آرایشگرها...</p>
                        </div>

                        <!-- end categories -->

                        <!-- تقویم هفته -->
                        <!-- تقویم هفته -->
                        <div class="mb-3 week-container d-none" id="weekContainer">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <button class="btn btn-outline-secondary btn-sm" id="prevMonth">‹‹ ماه قبل</button>
                                <span class="fw-bold" id="monthTitle">مهر</span>
                                <button class="btn btn-outline-secondary btn-sm" id="nextMonth">ماه بعد ››</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <button class="btn btn-outline-secondary btn-sm" id="prevWeek">‹ هفته قبل</button>
                                <span></span>
                                <button class="btn btn-outline-secondary btn-sm" id="nextWeek">هفته بعد ›</button>
                            </div>
                            <div class="day-slider" id="daySlider"></div>
                        </div>
                        <div id="daysLoader" class="text-center my-3 d-none">
                            <div class="spinner-border spinner-border-sm text-secondary"></div>
                            <p class="mt-2 text-muted">در حال بررسی روزهای قابل رزرو...</p>
                        </div>

                        <!-- ساعت‌های قابل رزرو -->
                        <div class="mt-4">
                            <h6 class="mb-3">زمان مورد نظر خود را انتخاب کنید</h6>
                            <div id="timeSlots" class="time-slots-container">
                                <p class="text-muted text-center w-100"></p>
                            </div>
                        </div>
                        <div id="slotsLoader" class="text-center my-3 d-none">
                            <div class="spinner-border spinner-border-sm text-secondary"></div>
                            <p class="mt-2 text-muted">در حال بارگذاری ساعت‌ها...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white sticky-bottom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-outline-primary d-none" id="prevStep">مرحله قبل</button>
                    <button type="button" class="btn btn-primary {{ $service->price_max ? '' : 'd-none' }}" id="nextStep">مرحله بعد</button>
                    <button type="button" class="btn btn-success {{ $service->price_max ? 'd-none' : '' }}" id="confirmReserve">رزور نوبت  </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('asset/js/reservation.js') }}"></script>
    <script src="https://lib.arvancloud.ir/Swiper/9.0.5/swiper-bundle.js"></script>

    <script>
        const serviceHasPriceRange = @json(!is_null($service->price_max));
        service_name = "{{ $service->name }}";
        $(document).ready(function() {
            // فرض کنید service_id از جایی گرفته می‌شود
            reservationState.service_id = {{ $service->id }}; // این مقدار باید از صفحه اصلی گرفته شود
        });
        var splide2 = new Splide('#categories', {
            direction: 'rtl',
            perPage: 4,
            // autoWidth: true,
            arrows: false,
            pagination: false,
            padding: '1rem',
            breakpoints: {
                768: {
                    perPage: 4,
                    gap: '.7rem',
                    arrows: false,
                },
                390: {
                    perPage: 3,
                    arrows: false,
                    gap: '.7rem',
                },
            },
        });
        splide2.mount();
    </script>
    <script>
        $(document).ready(function() {
            // راه اندازی Swiper برای گالری تصاویر
            var galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            var galleryTop = new Swiper('.gallery-top', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs
                }
            });

            // راه اندازی Swiper برای مودال گالری
            var galleryModalSwiper = new Swiper('.gallery-modal-swiper', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // هنگامی که مودال گالری باز می‌شود، اسلایدر را به اسلاید مربوطه منتقل می‌کند
            $('#galleryModal').on('shown.bs.modal', function() {
                var activeIndex = galleryTop.activeIndex;
                galleryModalSwiper.slideTo(activeIndex);
            });
        });
    </script>
    {{-- favorites --}}
    <script>
        $(document).on("click", ".like-btn", function() {
            const btn = $(this);
            const serviceId = btn.data("service-id");

            $.ajax({
                url: "/favorites/toggle",
                method: "POST",
                data: {
                    service_id: serviceId,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(res) {
                    if (res.status === "added") {
                        btn.addClass("active");
                        btn.find("i")
                            .removeClass("bi-heart")
                            .addClass("bi-heart-fill");
                    } else {
                        btn.removeClass("active");
                        btn.find("i")
                            .removeClass("bi-heart-fill")
                            .addClass("bi-heart");
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "خطا",
                        text: "لطفاً وارد حساب کاربری شوید",
                    });
                },
            });
        });
    </script>

    {{-- comments --}}
    <script>
        function nameinput(id) {
            const input = document.getElementById("searchInput" + id);
            const box = document.getElementById("autocompleteBox" + id);
            const clearBtn = document.getElementById("clearBtn_" + id);
            if (input.value.length > 0) {
                box.classList.add("filled");
                clearBtn.style.display = "block";
            } else {
                box.classList.remove("filled");
                clearBtn.style.display = "none";
            }
        }

        function clearInput(id) {
            const box = document.getElementById("autocompleteBox" + id);
            box.classList.remove("filled");
            const input = document.getElementById("searchInput" + id);
            input.value = "";
            const clearBtn = document.getElementById("clearBtn_" + id);
            clearBtn.style.display = "none";

            if (id == "state") {
                const box2 = document.getElementById("autocompleteBoxcity");
                const input2 = document.getElementById("searchInputcity");
                input2.value = "";
                document.getElementById("selectedIdcity").value = "";
                box2.classList.remove("filled");
                const clearBtn2 = document.getElementById("clearBtn_city");
                clearBtn2.style.display = "none";
            }
        }

        let selectedRating = 0;

        // ⭐ انتخاب امتیاز
        $(document).on("click", ".star", function() {
            selectedRating = $(this).data("value");
            $("#ratingValue").val(selectedRating);

            $(".star").each(function() {
                $(this).toggleClass("active", $(this).data("value") <= selectedRating);
            });
        });

        // 📩 ارسال دیدگاه
        $("#submitComment").on("click", function() {
            const text = $("#searchInputdescription").val().trim();
            const score = $("#ratingValue").val();

            if (!text) {
                Swal.fire({
                    title: "خطا",
                    text: "متن دیدگاه خود را وارد کنید.",
                    icon: "error",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    width: 400,
                });
                return;
            }

            if (score == 0) {
                Swal.fire({
                    title: "خطا",
                    text: "لطفا امتیاز خود را ثبت کنید",
                    icon: "error",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    width: 400,
                });
                return;
            }

            $.ajax({
                url: "/comments/store",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    text: text,
                    score: score,
                    commentable_type: "App\\Models\\Service", // مثال
                    commentable_id: 1 // آی‌دی سرویس
                },
                success: function() {
                    Swal.fire({
                        title: "موفق",
                        text: "دیدگاه شما با موفقیت ثبت شد و پس از تایید نمایش داده میشود.",
                        icon: "success",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        width: 400,
                    });

                    // ریست فرم
                    $("#searchInputdescription").val("");
                    $("#ratingValue").val(0);
                    $(".star").removeClass("active");
                },
                error: function() {
                    Swal.fire({
                        title: "خطا",
                        text: "مشکلی در ارسال دیدگاه رخ داد.",
                        icon: "error",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        width: 400,
                    });
                    // ریست فرم
                    $("#searchInputdescription").val("");
                    $("#ratingValue").val(0);
                    $(".star").removeClass("active");
                }
            });
        });
    </script>
@endsection
