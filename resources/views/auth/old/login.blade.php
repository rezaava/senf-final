@php
    use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ورود</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/fbc05d3d5f.js" crossorigin="anonymous"></script>
    {{-- sweet alert --}}
    <style>
        a {
            text-decoration: none;
        }

        .alert {
            top: 20px;
            right: 50px;
            position: absolute;
        }

        body {
            background-color: #e2e2e2;
        }

        .form {
            max-width: 400px;
            max-height: 580px;
            background: white;
            /* backdrop-filter: blur(30px); */
        }

        span {
            font-size: 14px
        }

        .btn {
            background-color: #003652;
            color: #ffffff;
        }

        .btn:hover {
            border: 2px solid #003652;
            color: #003652;
        }
    </style>

</head>

<body>

    <div class="container">
        <div class="d-flex align-items-center justify-content-center min-vh-100">
            <form action="{{ route('signin') }}" method="post" class="text-center form p-4 border shadow rounded-4">
                @csrf
                {{-- @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger">
                        {{ $errors->first('g-recaptcha-response') }}
                    </span>
                @endif --}}
                {{-- <a href="{{ url()->previous() }}" class="text-reset back"><i class="fa-solid fa-chevron-right"></i></a>
                <a href="{{ route('dashboard') }}"><img src="{{ asset('files/logo.svg') }}" class="w-50 mt-4"
                        alt=""></a> --}}
                <h3 class="">ورود</h3>
                <!-- phone number -->
                <p class="form-p mb-2 mt-4 pb-0 ">لطفا شماره موبایل خود را وارد کنید</p>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-4" id="mobile" placeholder="Enter phone number"
                        name="mobile" required value="{{old('mobile')}}">
                    <label for="mobile">شماره موبایل </label>
                    @error('mobile')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-4" id="password" placeholder="Enter password"
                        name="password" required>
                    <label for="password">رمز ورود </label>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> --}}
                {{-- <div class="mt-3 d-flex justify-content-center">
                    {!! NoCaptcha::display() !!}
                </div> --}}
                <div class="form-group mt-3">
                    <label for="captcha">کد کپچا را وارد نمایید:</label>
                    <div class="d-flex align-items-center text-center">
                        <span class="w-100">{!! captcha_img('default') !!}</span>
                        <button type="button" class="btn btn-light ms-2" id="refresh-captcha">
                            ↻
                        </button>
                    </div>
                    <input type="text" name="captcha" class="form-control mt-2" required>
                    @error('captcha')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn mt-3 mb-2 border py-2 rounded-3 w-100">
                    ارسال کد تایید
                </button>
                {{-- <span class="">هنوز ثبت نام نکردید؟<a href="{{ route('signup') }}" class="mx-3">ثبت
                        نام</a></span> --}}
                <p class="form-p mt-2">ورود شما به معنای پذیرش شرایط سایت و قوانین حریم خصوصی است</p>
            </form>
        </div>
    </div>

    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        document.getElementById('refresh-captcha').onclick = function() {
            fetch('/refresh-captcha')
                .then(res => res.text())
                .then(data => {
                    document.querySelector('span').innerHTML = data;
                });
        };
    </script>
    {{-- {!! NoCaptcha::renderJs() !!} --}}
</body>

</html>
