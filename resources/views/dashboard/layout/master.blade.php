<!DOCTYPE html>
<html lang="fa" dir="rtl">

@include('dashboard.layout.head')

<body>

    @include('dashboard.layout.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="header d-flex justify-content-between align-items-center mb-5 mt-md-0" style="margin-top: 3.5rem">
            <div>
                <h3 style="font-family: dana-lg">@yield('onvan') </h3>
                @yield('title-small')
                
            </div>
            <div class="user-info">
                <img src="{{ asset('img/hamayesh.jpg') }}" class="user-avatar" alt="User">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <span class="user-name"> {{Auth::user()->name}}</span>
                    <span style="background-color: rgba(173, 216, 230, 0.248);color:#888888;border-radius: 1rem ; padding:0 0.5rem;font-size: 0.76rem;margin-top: 0.5rem">
                        {{Auth::user()->roles->first()?->display_name}}
                    </span>
                </div>
            </div>
        </div>
        @yield('main')
        @yield('body')
    </div>
    

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('boot/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> --}}
        <script src="https://lib.arvancloud.ir/sweetalert2/9.17.4/sweetalert2.all.min.js"></script>
    <!-- Custom JS -->
    <script src="{{asset('script/script.js')}}"></script>
    @yield('script')
    @yield('javaScript')
</body>
</html>
