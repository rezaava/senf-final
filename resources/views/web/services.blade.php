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
    </style>
    <link rel="stylesheet" href="{{ asset('asset/css/search.css') }}">
@endsection
@section('content')
    <div class="container py-4 px-2" style="min-height: 100dvh">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3 bg-white p-3 rounded-4 shadow-sm">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/">خانه</a></li>
                <li class="breadcrumb-item">خدمات</li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
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
@endsection
