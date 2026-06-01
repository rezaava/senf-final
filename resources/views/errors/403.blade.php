<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | دسترسی غیر مجاز</title>

    {{-- Bootstrap 5 --}}
    <link href="https://lib.arvancloud.ir/bootstrap/5.3.0-alpha1/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>

        @font-face {
    font-family: IRANSansX;
    font-style: normal;
    font-weight: 100;
    src: url("fonts/woff/IRANSansXFaNum-thin.woff") format("woff"),
        url("fonts/woff2/IRANSansXFaNum-thin.woff2") format("woff2");
}

        body{
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: IRANSansX;
        }

        .error-box{
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        }

        .image-side{
            background: #eef2ff;
            min-height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .image-placeholder{
            width: 100%;
            max-width: 320px;
            height: 320px;
            border: 3px dashed #6c63ff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c63ff;
            font-size: 20px;
            text-align: center;
            background: white;
        }

        .content-side{
            padding: 50px 35px;
        }

        .error-code{
            font-size: 90px;
            font-weight: bold;
            color: #007DFE;
            line-height: 1;
        }

        .btn-home{
            padding: 12px 28px;
            border-radius: 12px;
        }

        @media(max-width: 768px){

            .image-side{
                min-height: 250px;
            }

            .error-code{
                font-size: 70px;
            }

            .content-side{
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <div class="row g-0 error-box">

                {{-- Image Section --}}
                <div class="col-md-6 image-side">

                    {{-- عکس خودتو اینجا بزار --}}
                    <img src="{{ asset('images/403.png') }}" class="img-fluid" style="border-radius: 1rem;">

                    {{-- <div class="image-placeholder">
                        جای عکس شما
                    </div> --}}

                </div>

                {{-- Content Section --}}
                <div class="col-md-6 d-flex align-items-center">
                    <div class="content-side w-100">

                        <div class="error-code">
                            403
                        </div>

                        <h2 class="fw-bold mb-3">
                            دسترسی غیر مجاز
                        </h2>

                        <p class="text-muted mb-4">
                            شما اجازه دسترسی به این بخش را ندارید.
                            در صورت نیاز با مدیر سیستم تماس بگیرید.
                        </p>

                        <a href="{{ url('/') }}" class="btn btn-danger btn-home" style="background-color: #007DFE;">
                            بازگشت به صفحه اصلی
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>