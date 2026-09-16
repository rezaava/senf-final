@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/notification.css') }}">
    <script src="{{ asset('asset/js/notification.js') }}"></script>

    <style>
          body{
            overflow: hidden;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            max-width: 28rem;
            height: 100vh;
            background: #C79493;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .overlay.hide {
            opacity: 0;
            visibility: hidden;
        }

        .overlay img {
            animation: logoAnimation 1.5s infinite ease-in-out;
        }
    </style>
@endsection
@section('content')
<div class="overlay position-absolute" id="loader">
            <img id="img" class="w-50" src="{{ asset('images/logo.png') }}" alt="logo">
</div>

    
    <div class="container">
        <div class="header-main mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-icon">
                    <button class="bg-transparent border-0" id="notificationButton" data-bs-toggle="modal"
                        data-bs-target="#notificationModal">
                        <i class="fa-solid fa-bell text-muted"></i>
                        <span class="badge rounded-pill message-badge" id="notificationBadge">2</span>
                    </button>
                </div>
                <div class="header-icon header-location">
                    <button class="bg-transparent border-0" data-bs-toggle="modal" data-bs-target="#cityModal">
                        <i class="fa-solid fa-location-dot text-muted"></i>
                        {{ session('user_city', 'تهران') }}
                    </button>
                </div>
                <!-- <div class="header-icon">
                                                                <i class="bi bi-person-fill fs-3 text-muted"></i>
                                                            </div> -->
                <div class="two mb-3">
                    <h1>brand name
                        <!-- <span>Example Tagline Text</span> -->
                    </h1>
                </div>
            </div>
        </div>
        <!-- start Special Offer Banner -->
        @if ($sliders?->count() > 0)
            <div class="special-offer d-flex align-items-center mb-3">
                <div class="splide" id="slider" role="group" aria-label="Splide Basic HTML Example">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($sliders as $slider)
                                <li class="splide__slide" >
                                    <a href="{{ $slider->link }}">
                                        <img src="{{ asset($slider->image) }}" class="w-100 rounded-4"
                                            alt="{{ $slider->title }}" style="width: 100%;height: 100%;object-fit: cover">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <!-- end Special Offer Banner -->
        <!-- start categories -->
        <div class="scrollable-services mb-3 mt-2">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-white ps-2 mb-0 main-title">دسته بندی های خدمات</h5>
                <!-- <i class="bi bi-arrow-left"></i> -->
            </div>
            <div class="row justify-content-center g-0">
                @foreach ($categories as $category)
                    <div class="col-4 px-2 mb-3 text-center">
                        <a href="{{ route('category', $category) }}">
                            <div class="service-card">
                                <div class="service-icon">
                                    <img src="{{ asset($category->image ?? 'files/no-image.png') }}"
                                        alt="{{ $category->name }}" width="65">
                                </div>
                                {{ $category->name }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- end categories -->
        <!-- start adds -->
        <!-- end adds -->
        <!-- start search section -->
    {{--     <div class="search-container mb-3">
            <div class="tabs pt-3">
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
                <!-- تب آرایشگاه‌ها -->
                <div class="tab-pane" id="salons">
                    <div class="input-group">
                        <input type="text" class="input-field" id="salon-search" placeholder=" ">
                        <label class="input-label" for="salon-search">جستجوی نام آرایشگاه</label>
                        <i class="input-icon fas fa-search"></i>
                        <button class="clear-btn">&times;</button>
                    </div>
                    <!-- <button class="search-btn">جستجو</button> -->
                    <button class="main-btn btn">جستجوی آرایشگاه</button>

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

                    <!-- <button class="search-btn">جستجو</button> -->
                    <button class="main-btn btn">جستجوی ارایشگاه</button>

                </div>
            </div>
        </div>  --}}
        <!-- end search section -->

        <!-- some organs -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-muted ps-2 mb-0 main-title">برترین آرایشگر ها</h5>
                <!-- <i class="bi bi-arrow-left"></i> -->
            </div>
            <div id="tops-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($top_operators as $operator)
                            <li class="splide__slide">
                                <a href="#" class="text-reset text-decoration-none">
                                    <div class="card rounded-4 w-100">
                                        <img class="card-img-top rounded-4 rounded-bottom"
                                            src="{{ asset($operator->image ?? 'files/no-image.png') }}"
                                            alt="{{ $operator->name }}">
                                        <div class="card-body">
                                            <h6 class="m-0">{{ $operator->name }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-muted ps-2 mb-0 main-title">آرایشگاه های نزدیک شما</h5>
                <!-- <i class="bi bi-arrow-left"></i> -->
            </div>
            <div id="product-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($top_organs as $organ)
                            <li class="splide__slide">
                                <a href="{{ route('salon', ['salon' => $organ]) }}"
                                    class="text-reset text-decoration-none">
                                    <div class="card rounded-4 w-100">
                                        <img class="card-img-top"
                                            src="{{ asset($organ->image ?? 'files/no-image.png') }}" alt="Card image">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="m-0">{{ $organ->name }}</h6>
                                                <span class="d-flex text-warning">
                                                    <i class="fa-solid fa-star fa-xs"></i>
                                                    <i class="fa-solid fa-star fa-xs"></i>
                                                    <i class="fa-solid fa-star fa-xs"></i>
                                                    <i class="fa-regular fa-star fa-xs"></i>
                                                    <i class="fa-regular fa-star fa-xs"></i>
                                                </span>
                                            </div>
                                            <p class="card-text fs-6 text-muted">{{ $organ->address }}</p>
                                            <div class="d-flex justify-content-end gap-2">
                                                @foreach ($organ->Categories as $cat)
                                                    <span class="tag">{{ $cat->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- end some organs -->
        <!-- مدال نوتیفیکیشن -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content modal-content-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title modal-title-custom" id="notificationModalLabel">
                            <i class="bi bi-bell me-2"></i>پیام ها
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body notification-modal-body">
                        <div class="notification-header">
                            <h6>پیام‌های اخیر</h6>
                            <button type="button" class="btn mark-all-read-btn" id="markAllReadBtn">
                                <i class="bi bi-check-all me-1"></i>خواندن همه
                            </button>
                        </div>

                        <div class="notification-list" id="notificationList">
                            <!-- لیست نوتیفیکیشن‌ها از طریق JavaScript پر می‌شود -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cityModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">

                    <div class="modal-header">
                        <h5 class="modal-title">انتخاب شهر</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="text" id="citySearch" class="form-control mb-3" placeholder="جستجوی شهر...">

                        <div id="cityList" style="max-height:300px; overflow-y:auto;">

                            @foreach ($cities as $city)
                                <div class="city-item p-2 border-bottom cursor-pointer" data-city="{{ $city->title }}">
                                    {{ $city->title }}
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary w-100" id="confirmCity">
                            تغییر شهر
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

<script>
            window.addEventListener("load", function () {
                let loader = document.querySelector('#loader');
                let img = document.querySelector('#img');
                document.body.style.overflow = "hidden";

                setTimeout(() => {
                    loader.classList.add("hide");
                    document.body.style.overflow = "auto";
                    setTimeout(() => {
                        loader.remove();
                    }, 800);

                }, 1000);

            });
</script>

    <script>
        // مدیریت تب‌ها
        $(".tab").on("click", function() {
            const tabId = $(this).data("tab");

            $(".tab").removeClass("active");
            $(this).addClass("active");

            $(".tab-pane").removeClass("active");
            $(`#${tabId}`).addClass("active");
        });
        document.addEventListener('DOMContentLoaded', function() {
            var splide = new Splide('#slider', {
                type: 'loop',
                direction: 'rtl',
            });
            splide.mount();
        });

        new Splide('#tops-slider', {
            perPage: 2,
            gap: '1rem',
            arrows: false,
            pagination: false,
            drag: true,
            direction: 'rtl',
            padding: {
                left: '8rem',
                right: '0'
            },
            breakpoints: {
                768: {
                    perPage: 2,
                },
                480: {
                    perPage: 2,
                    padding: {
                        left: '2rem',
                        right: '0'
                    },
                }
            }
        }).mount();
        new Splide('#product-slider', {
            perPage: 1,
            gap: '1rem',
            arrows: false,
            pagination: false,
            drag: true,
            direction: 'rtl',
            padding: {
                left: '8rem',
                right: '0'
            },
            breakpoints: {
                768: {
                    perPage: 2.2,
                },
                480: {
                    perPage: 1,
                }
            }
        }).mount();
    </script>
    {{-- انتخاب شهر --}}
    <script>
        let selectedCity = null;

        document.querySelectorAll('.city-item').forEach(item => {
            item.addEventListener('click', function() {

                document.querySelectorAll('.city-item')
                    .forEach(i => i.classList.remove('bg-light'));

                this.classList.add('bg-light');

                selectedCity = this.dataset.city;
            });
        });

        document.getElementById('citySearch')
            .addEventListener('keyup', function() {

                let value = this.value.toLowerCase();

                document.querySelectorAll('.city-item').forEach(item => {
                    item.style.display =
                        item.textContent.toLowerCase().includes(value) ?
                        'block' :
                        'none';
                });
            });

        document.getElementById('confirmCity')
            .addEventListener('click', function() {

                if (!selectedCity) {
                    alert('لطفا یک شهر انتخاب کنید');
                    return;
                }

                fetch("{{ route('set.city') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            city: selectedCity
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });

            });
    </script>
    {{-- باکس سرچ --}}
    <script>
        document.querySelector('#salons .main-btn')
            .addEventListener('click', function() {

                const name = document.getElementById('salon-search').value;

                const url = new URL('/search', window.location.origin);
                url.searchParams.append('tab', 'salons');
                url.searchParams.append('name', name);

                window.location.href = url.toString();
            });

        document.querySelector('#services .main-btn')
            .addEventListener('click', function() {

                const selectedItem =
                    document.querySelector('#category-list .selected');

                const categoryId =
                    selectedItem ? selectedItem.dataset.id : '';

                const date =
                    document.getElementById('service-date').value;

                const url = new URL('/search', window.location.origin);
                url.searchParams.append('tab', 'services');
                url.searchParams.append('category', categoryId);
                url.searchParams.append('date', date);

                window.location.href = url.toString();
            });
    </script>
@endsection
