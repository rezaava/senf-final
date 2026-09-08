@extends('web.layouts.master')
@section('head')
    <title>دخدمات دسته بندی {{ $category->name }}</title>
    <style>
        .card-custom:hover {
            transform: translateY(-2px);
            transition: 0.2s ease;
        }

        .offcanvas.offcanvas-bottom {
            height: 100dvh;
        }

        :root {
            --primary: var(--color-primary);
            --primary-dark: var(--color-primary-dark);
            --secondary: var(--color-secondary);
        }

        /* checkbox custom */
        .filter-check .form-check-input {
            border-color: var(--primary);
        }

        .filter-check .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .offcanvas {
            background-color: var(--color-surface);
        }

        .special-offer {
            width: 100%;
            padding: 0;
            margin: 0 0 1rem 0 !important;
        }

        .special-offer {
    width: 100%;
    margin: 0 0 1rem 0 !important;
    padding: 0;
}

.special-offer {
    width: 100%;
    margin: 0 !important;
    padding: 0;
}

#slider {
    width: 100%;
    max-width: 100%;
    margin: 0;
    padding: 0;
    border-radius: 18px;
}

#slider .splide__track,
#slider .splide__list {
    width: 100%;
    margin: 0;
    padding: 0;
}

#slider .splide__slide {
    height: 100px;
    width: 100%;
    overflow: hidden;
    border-radius: 18px;
    background-color: #000; /* یا هر رنگی که با پس‌زمینه عکس‌هاتون ست باشه */
}

#slider .slider-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
}
    </style>
    <link rel="stylesheet" href="{{ asset('asset/css/search.css') }}">
@endsection
@section('content')
    <div class="container py-4 px-2" style="min-height: 100dvh">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3 bg-dark shadow-sm p-0" style="border-radius: 18px;">
            {{-- <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/">خانه</a></li>
                <li class="breadcrumb-item">خدمات</li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol> --}}
            @if ($sliders?->count() > 0)
                <div class="special-offer d-flex align-items-center justify-content-center mb-3">
                    <div class="splide" id="slider" role="group" aria-label="Splide Basic HTML Example">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($sliders as $slider)
                                    <li class="splide__slide" >
                                        <a href="{{ $slider->link }}">
                                            <img src="{{ asset($slider->image) }}" class="w-100 rounded-4 slider-img" alt="{{ $slider->title }}">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </nav>

        <!-- Filters and sort -->
        <div class="row g-0">
            <div class="col-md-12">
                <div class="sort-options rounded-4">
                    <!-- Button to open the offcanvas sidebar -->
                    <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#filtersOffcanvas">
                        <i class="bi bi-funnel"></i>
                        فیلتر
                    </button>
                    <div class="me-2">مرتب‌سازی بر اساس:</div>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}"
                        class="sort-btn {{ request('sort') === 'rating' ? 'active' : '' }}">
                        بالاترین امتیاز
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                        class="sort-btn {{ request('sort') === 'price_asc' ? 'active' : '' }}">
                        ارزان‌ترین
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                        class="sort-btn {{ request('sort') === 'price_desc' ? 'active' : '' }}">
                        گران‌ترین
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                        class="sort-btn {{ request('sort') === 'latest' ? 'active' : '' }}">
                        جدیدترین
                    </a>
                </div>
            </div>
        </div>
        <!-- Services List -->
        <div class="row g-0">

            <!-- Card -->
            @foreach ($services as $service)
                <div class="col-md-12 mb-2">
                    <div class="card-custom shadow-sm bg-white p-3 rounded-4">
                        <a href="{{ route('service', $service) }}" class="text-decoration-none text-reset">
                            <div class="d-flex gap-3">
                                <div class="img" style="width:40%">
                                    <img src="{{ asset($service->image ?? 'files/no-image.png') }}" alt="test"
                                        class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold">{{ $service->name }}</h5>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold text-muted">{{ number_format($service->price) }} تومان</span>
                                    </div>

                                    <div class="text-muted mb-2">
                                        <i class="bi bi-clock"></i> مدت زمان: {{ $service->time }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <hr>
                        <div class="d-flex gap-3">
                            <a href="{{ route('salon', $service->organ) }}">
                                <img src="{{ asset($service->organ->image ?? 'files/no-image.png') }}"
                                    style="width:60px;height:60px;border-radius:8px;object-fit:cover">
                            </a>
                            <div>
                                <a href="{{ route('salon', $service->organ) }}">
                                    <h6 class="fw-bold mb-1">{{ $service->organ->name }}</h6>
                                </a>
                                <div class="text-muted mb-1">
                                    <i class="bi bi-geo-alt"></i> {{ $service->organ->address }}
                                </div>
                                <div class="text-warning">
                                    @php
                                        $comments = $service->organ->comments()->where('is_approved', true)->get();
                                        $score =
                                            $comments->sum('score') / ($comments->count() > 0 ? $comments->count() : 1);
                                    @endphp
                                    <small class="text-muted">
                                        ({{ $score }})
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $score ?? 0)
                                                <i class="fa-solid fa-star text-warning small"></i>
                                            @else
                                                <i class="fa-regular fa-star text-warning small"></i>
                                            @endif
                                        @endfor
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <!-- Offcanvas filters -->
    <div class="offcanvas offcanvas-bottom offcanvas-lg" tabindex="-1" id="filtersOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">
                <i class="bi bi-funnel me-1"></i> فیلتر نتایج
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <form method="GET" action="{{ url()->current() }}">
            <div class="offcanvas-body">

                <!-- Categories -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">دسته‌بندی خدمات</h6>

                    <div class="row">
                        @foreach ($categories as $category)
                            <div class="col-6 col-md-4 mb-2">
                                <div class="form-check filter-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        value="{{ $category->id }}" id="cat{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">محدوده قیمت (تومان)</h6>

                    <div class="d-flex gap-2">
                        <input type="number" class="form-control" name="price_from" placeholder="از"
                            value="{{ request('price_from') }}">

                        <input type="number" class="form-control" name="price_to" placeholder="تا"
                            value="{{ request('price_to') }}">
                    </div>
                </div>

                <!-- Today Only -->
                <div class="mb-4">
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="checkbox" name="today" value="1" id="today"
                            {{ request('today') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="today">
                            فقط قابل رزرو امروز
                        </label>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="p-3 border-top d-flex gap-2">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-50">
                    حذف فیلترها
                </a>
                <button class="btn btn-primary w-50">
                    اعمال فیلتر
                </button>
            </div>
        </form>
    </div>

@endsection
@section('scripts')
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
            perPage: 1,
            gap: '10px',
            pagination: false,
            autoplay: true,
            interval: 2000,
            arrows: false,
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
            left: '1rem',
            right: '0'
        },
        breakpoints: {
            768: {
                perPage: 2,
            },
            480: {
                perPage: 2,
                padding: {
                    left: '1rem',
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
@endsection
