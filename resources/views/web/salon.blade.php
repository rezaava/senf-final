@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/organ.css') }}">
    <link rel="stylesheet" href="https://lib.arvancloud.ir/Swiper/9.0.5/swiper-bundle.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
    <div class="profile-bg w-100 position-relative">
        <img src="{{ asset('asset/images/banner1.png') }}" alt="test" class="w-100 h-100 object-fit-cover">
        <div class="position-absolute top-0 w-100 px-3 pt-2 d-flex justify-content-between align-items-center">
            <div class="text-center stat-divider">
                <a href="{{ route('home') }}">
                    <div class="back-search-icon rounded-circle">
                        <i class="bi bi-chevron-right start-0"></i>
                    </div>
                </a>
            </div>
            {{-- <div class="text-center stat-divider">
                <div class="back-search-icon rounded-circle">
                    <i class="bi bi-search start-0"></i>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- profile -->
    <div class="container" style="position: relative;bottom: 30px;">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm rounded-4 px-4 py-3 mb-5">
                    <div class="row align-items-center">
                        <div class="col-12 justify-content-start align-items-center">
                            <img src="{{ asset($salon->image ?? 'files/no-image.png') }}"
                                class="rounded-circle profile-avatar">
                            <h5 class="fw-bold text-dark d-inline ps-2">{{ $salon->name }}</h5>
                        </div>
                        <div class="col-12 mt-3 mt-md-0">
                            <div class="d-flex justify-content-between align-items-center position-relative mb-3 mt-2">
                                @php
                                    $comments = $salon->comments()->where('is_approved', true)->get();
                                    $score =
                                        $comments->sum('score') / ($comments->count() > 0 ? $comments->count() : 1);
                                @endphp
                                <span class="ms-2">{{ $score }}
                                    <small class="ms-2"
                                        style="font-size: 10px;"> ({{ $comments->count() }} نظر) </small>
                                </span>
                                <div class="text-center stat-divider d-flex align-items-center">
                                    <h6 class="fw-bold me-2 mb-0">
                                        <i class="bi bi-star-fill"></i>
                                        {{ $score }}
                                    </h6>
                                    <small class="start-num">({{ number_format($comments->count()) }})</small>
                                </div>
                                <div class="text-center stat-divider">
                                    <a href="#comment-section">
                                        <div class="comment-arrow rounded-circle">
                                            <i class="bi bi-chevron-left"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <p class="card-text fs-6 text-muted">{{ $salon->address }}</p>
                            <div class="d-inline-flex gap-2 flex-wrap justify-content-center justify-content-md-start">
                                <span class="badge organ-badge bg-opacity-10">فعال</span>
                                <span class="badge organ-badge bg-opacity-10">ویژه</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- main -->
    <div class="container mt-0 position-relative" style="position: relative;bottom: 55px;">
        <div class="row">
            <div class="col-12 col-md-12 mb-4">
                <!-- تب خدمات -->
                <div class="card shadow-sm p-3 pt-1 rounded-4 mb-4">
                    <ul class="nav nav-tabs mb-3 pe-0" id="tabOne" role="tablist">
                        @foreach ($salon->categories as $key => $cat)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tabone-link text-dark {{ $key == 0 ? 'active' : '' }}"
                                    id="{{ $cat->id }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#{{ $cat->id }}" type="button"
                                    role="tab">{{ $cat->name }}</button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="tabOneContent">
                        @foreach ($salon->categories as $key => $cat)
                            <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="{{ $cat->id }}"
                                role="tabpanel">
                                @foreach ($cat->services()->where('organ_id',$salon->id)->get() as $service)
                                    <div class="organ-service-card d-flex">
                                        <a href="{{ route('service', ['service' => $service]) }}"
                                            class="text-reset text-decoration-none" style="width: 40%;">
                                            <img src="{{ asset($service->image ?? 'files/no-image.png') }}"
                                                class="service-image" alt="{{ $service->name }}">
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
                                                {{ number_format($service->price) }}
                                                {{ number_format($service->price_max) > 0 ? ' تا ' . number_format($service->price_max) : '' }}
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
                        @endforeach
                    </div>
                </div>
                <!-- گالری تصاویر -->
                <div class="card shadow-sm p-3 rounded-4 mb-4">
                    <h6 class="fw-bold mb-3">گالری تصاویر</h6>
                    <div class="row g-2 mb-2">
                        <!-- تصاویر گالری کوچک -->
                        @foreach ($gallery as $key => $item)
                            <div class="col-4">
                                <img src="{{ asset($item->path) }}" class="img-fluid rounded-3 gallery-img"
                                    alt="{{ $item->title }}" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                    data-index="{{ $key }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="gallery-link small d-flex align-items-center justify-content-end" data-bs-toggle="modal"
                        data-bs-target="#galleryModal">
                        به گالری بروید <i class="bi bi-arrow-left me-1" style="position: relative;top: 3px;"></i>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header justify-content-between">
                                <h6 class="m-0">گالری تصاویر</h6>
                                <button type="button" class="btn-close ms-0 ps-0" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <!-- Swiper -->
                                <div class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($gallery as $key => $item)
                                            <div class="swiper-slide">
                                                <img src="{{ asset($item->path) }}" alt="{{ $item->title }}"
                                                    class="w-100 h-100 object-fit-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- پایان گالری -->
                <!-- comment start -->
                <div class="card shadow-sm p-3 rounded-4 mb-4" style="padding-left: 10px !important;"
                    id="comment-section">
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
                    <div class="row g-2 mb-2 comments-row ps-1" style="margin-left: 1px;">
                        <!-- Display Comments -->
                        @foreach ($comments as $comment)
                            <div class="comment-card">
                                <div class="comment-header">
                                    <img src="{{ asset($comment->user->image ?? 'files/no-image.png') }}"
                                        class="comment-avatar" alt="{{ $comment->name }}">
                                    <div class="w-100 d-flex justify-content-between">
                                        <h6 class="mb-0">{{ $comment->name }}</h6>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $comment->score ?? 0)
                                                    <i class="fa-solid fa-star text-warning small"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-warning small"></i>
                                                @endif
                                            @endfor
                                            <span
                                                class="text-muted small ms-2">{{ Jdate($comment->created_at)->ago() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $comment->text }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- The Modal -->
                <div class="modal fade" id="myModal">
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
                                                <span class="clear-btn" id="clearBtn_description"
                                                    onclick="clearInput('description')" style="top: 23px;">×</span>
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

                                        <button type="button" class="btn btn-outline-danger px-4 py-2"
                                            data-bs-dismiss="modal">
                                            لغو
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- comment end -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://lib.arvancloud.ir/Swiper/9.0.5/swiper-bundle.js"></script>
    <script>
        // مقداردهی اولیه Swiper
        const mySwiper = new Swiper(".mySwiper", {
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            keyboard: true,
            loop: true,
        });

        // مدیریت رویداد کلیک روی تصاویر
        document.querySelectorAll('.gallery-img').forEach(img => {
            img.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                mySwiper.slideTo(index, 0); // +1 به دلیل فعال بودن loop
            });
        });
        // مدیریت رویداد کلیک روی لینک
        document.querySelector('.gallery-link').addEventListener('click', function() {
            mySwiper.slideTo(0, 0); // نمایش اولین تصویر
        });
        // مدیریت رویداد نمایش مدال
        const galleryModal = document.getElementById('galleryModal');
        galleryModal.addEventListener('show.bs.modal', function(event) {
            // اگر تصویری که کلیک شده، index دارد
            if (event.relatedTarget && event.relatedTarget.hasAttribute('data-index')) {
                const index = parseInt(event.relatedTarget.getAttribute('data-index'));
                mySwiper.slideTo(index, 0); // +1 به دلیل فعال بودن loop
            }
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
                    commentable_type: "App\\Models\\Organ", // مثال
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
