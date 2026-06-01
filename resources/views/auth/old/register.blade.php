<!DOCTYPE html>
<html lang="en">

<head>
    <title>ثبت نام</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/fbc05d3d5f.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            direction: rtl;
            text-align: right;
            background-color: #e2e2e2;
        }

        .form {
            max-width: 800px;
            /* max-height: 440px; */
            background: white;
            /* backdrop-filter: blur(30px); */
        }

        span {
            font-size: 14px
        }

        .btn {
            background-color: #003652;
            border: 2px solid #003652;
            color: #ffffff;
        }

        .btn:hover {
            border: 2px solid #003652;
            color: #003652;
        }

        .plan-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #d7d7d7;
        }

        .plan-card input {
            display: none;
        }

        .plan-card:hover,
        .plan-card input:checked+.card {
            background-color: #c7d8ff7f;
            border: 2px solid #003652;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .old-price {
            color: red;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .discount-badge {
            background-color: red;
            color: white;
            padding: 3px 6px;
            border-radius: 20px;
            font-size: 0.6rem;
        }
    </style>
</head>

<body>
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
    <div class="container p-3">
        <div class="d-flex align-items-center justify-content-center min-vh-100">
            <form action="{{ route('register') }}" class="text-center form p-4 border shadow rounded-4" method="post"
                autocomplete="off">
                @CSRF
                {{-- <a href="{{ url()->previous() }}" class="text-reset back"><i class="fa-solid fa-chevron-right"></i></a>
                <a href="{{ route('dashboard') }}"><img src="{{ asset('files/logo.svg') }}" class="w-50 mt-4"
                        alt=""></a> --}}
                <h3 class="mb-3">ثبت نام</h3>
                <!-- inputs -->
                <div class="row mt-3 text-end">
                    <!-- name -->
                    <div class="col-md-6 mt-2">
                        <label for="name" class="form-label">نام<span class="text-danger">*</span>
                            :</label>
                        <input type="text" class="form-control" id="name" placeholder="نام "
                            name="name" value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- phone -->
                    <div class="col-md-6 mt-2">
                        <label for="mobile" class="form-label"> شماره موبایل<span class="text-danger">*</span>
                            :</label>
                        <input type="text" class="form-control" id="mobile" placeholder="09xxxxxxxxx"
                            name="mobile" value="{{ old('mobile') }}">
                        @error('mobile')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- email --}}
                    <div class="col-md-6 mt-2">
                        <label for="email" class="form-label">ایمیل :</label>
                        <input type="email" class="form-control" id="email" placeholder="example@gmail.com"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- password -->
                    <div class="col-md-6 mt-2">
                        <label for="password" class="form-label">رمز ورود<span class="text-danger">*</span> :</label>
                        <input type="password" class="form-control" id="password" placeholder="حداقل 8 رقم"
                            name="password" value="{{ old('password') }}">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="btn mt-5 rounded-3 w-100">
                    ثبت نام
                </button>
                <span class="text-dark">اکانت دارید؟<a href="{{ route('login') }}"
                        class="text-reset mx-3">ورود</a></span>
                <p class="form-p mt-2">ورود شما به معنای پذیرش شرایط سایت و قوانین حریم خصوصی است</p>
            </form>
        </div>
    </div>


    {{-- datepicker --}}


    <script>
        $(document).ready(function() {
            $("#date").persianDatepicker({
                format: "YYYY/MM/DD",
                autoClose: true,
                toolbox: {
                    calendarSwitch: {
                        enabled: true // نمایش دکمه تغییر تقویم
                    }
                }
            });
        });
    </script>
</body>

</html>
