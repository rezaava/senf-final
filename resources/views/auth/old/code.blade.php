@php
    use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <title>کد تایید</title>
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
            <form action="{{route('code')}}" method="post" class="text-center form p-4 border shadow rounded-4">
                @csrf
                @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger">
                        {{ $errors->first('g-recaptcha-response') }}
                    </span>
                @endif
                {{-- <a href="{{ url()->previous() }}" class="text-reset back"><i class="fa-solid fa-chevron-right"></i></a>
                <a href="{{ route('dashboard') }}"><img src="{{ asset('files/logo.svg') }}" class="w-50 mt-4"
                        alt=""></a> --}}
                <h3 class="">کد تایید</h3>
                <!-- phone number -->
                <p class="form-p mb-2 mt-4 pb-0 ">لطفا کد تایید ارسال شده را وارد کنید</p>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-4" id="code" placeholder="Enter phone number"
                        name="code" required>
                    <label for="code">کد تایید </label>
                    @error('code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn mt-3 mb-2 border py-2 rounded-3 w-100" name="id" value="{{$user->id}}">
                    ورود
                </button>
                <span class="">هنوز ثبت نام نکردید؟<a href="{{ route('signup') }}" class="mx-3">ثبت
                        نام</a></span>
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
</body>

</html>
