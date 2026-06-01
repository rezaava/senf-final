<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آرایشگاه آنلاین</title>
    <!-- Bootstrap 5 RTL CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet"> --}}
    <link href="https://lib.arvancloud.ir/bootstrap/5.3.0-alpha1/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> --}}
    <link rel="stylesheet" href="https://lib.arvancloud.ir/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">

    <!-- persian datepicker -->
    {{-- <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"> --}}

    <!-- font awesome -->
    {{-- <script src="https://kit.fontawesome.com/fbc05d3d5f.js" crossorigin="anonymous"></script> --}}
    <script src="https://lib.arvancloud.ir/font-awesome/6.3.0/js/all.js"></script>

    <!-- custom styles -->
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/splide.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/inputs.css') }}">
    @yield('head')
</head>

<body>
    <div class="container main-container pb-5 position-relative"
        style="max-width: 28rem;background: url({{ asset('asset/images/back3.jpg') }}) no-repeat center center;min-height: 100dvh;">
        @yield('content')

        <!-- Bottom Navigation Bar -->
        <div class="nav-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col nav-item {{ Route::currentRouteName() == 'home' ? 'active' : '' }} ">
                        <a href="/" class="text-decoration-none text-reset">
                            {{-- <i class="bi bi-house-fill nav-icon"></i> --}}
                            <i class="fa-solid fa-house nav-icon"></i>
                            {{-- <div>خانه</div> --}}
                        </a>
                    </div>
                    <div class="col nav-item {{ Route::currentRouteName() == 'search' ? 'active' : '' }}">
                        <a href="{{ route('search') }}" class="text-decoration-none text-reset">
                            {{-- <i class="bi bi-search nav-icon"></i> --}}
                            <i class="fa-solid fa-magnifying-glass nav-icon"></i>
                            {{-- <div>جستجو</div> --}}
                        </a>
                    </div>
                    <div class="col nav-item {{ Route::currentRouteName() == 'cart' ? 'active' : '' }}">
                        <a href="{{ route('cart') }}" class="text-decoration-none text-reset position-relative">
                            {{-- <i class="bi bi-bag nav-icon"></i> --}}
                            <i class="fa-solid fa-cart-shopping nav-icon"></i>
                            <span class="badge bg-primary badge-pill" id="cart-badge"
                                style="position: absolute;right: -9px;top: 5px;">{{ $cartItems }}</span>

                            {{-- <div>سبد خرید</div> --}}
                        </a>
                    </div>
                    {{-- <div class="col nav-item">
                        <a href="/" class="text-decoration-none text-reset">
                            <!-- <i class="bi bi-house-door nav-icon"></i> -->
                            <i class="fa-solid fa-calendar-days nav-icon"></i>
                        </a>
                    </div> --}}
                    <div class="col nav-item {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}">
                        <a href="{{ route('profile') }}" class="text-decoration-none text-reset">
                            {{-- <i class="bi bi-person nav-icon"></i> --}}
                            @if(Auth::user())
                            <i class="fa-solid fa-user nav-icon"></i>
                            @else
                            <i class="fa-solid fa-right-to-bracket nav-icon"></i>
                            @endif

                            {{-- <div>پروفایل</div> --}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS Bundle with Popper -->
    <script src="{{ asset('asset/js/bootstrap.js') }}"></script>
    <script src="{{ asset('asset/js/splide.min.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://lib.arvancloud.ir/jquery/3.6.3/jquery.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script> --}}
    <script src="{{ asset('asset/js/inputs.js') }}"></script>
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script src="https://lib.arvancloud.ir/sweetalert2/9.17.4/sweetalert2.all.js"></script>
    @if (Session::has('success'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                text: "{{ Session::get('success') }}",
                showConfirmButton: false,
                width: 400,
                timer: 2000,
            });
        </script>
    @endif
    @if (Session::has('fail'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                text: "{{ Session::get('fail') }}",
                showConfirmButton: false,
                width: 400,
                timer: 2000,
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (!localStorage.getItem('user_location_set')) {

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        successLocation,
                        errorLocation, {
                            timeout: 8000
                        }
                    );
                } else {
                    setDefaultCity();
                }

            }

            function successLocation(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                fetch('/set-location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        lat,
                        lng
                    })
                });

                localStorage.setItem('user_location_set', true);
            }

            function errorLocation() {
                setDefaultCity();
            }

            function setDefaultCity() {
                fetch('/set-location', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                localStorage.setItem('user_location_set', true);
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
