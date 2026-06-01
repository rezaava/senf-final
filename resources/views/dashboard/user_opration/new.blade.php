@extends('dashboard.layout.master')
@section('onvan')
افزودن کاربر
@endsection
@section('title')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

    <title>users</title>
    <style>
        .submit-btn {
            background-color: #003652;
            color: #ffffff;
        }
    </style>
@endsection

@section('body')
    
    <!-- main  -->
    <div class="col">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
    @endif
        <!-- Form -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white pb-3">
            <div class="clearfix mt-2">
                <h5 class="float-end">افزودن کاربر</h5>
            </div>
            <form action="{{route('user.store')}}" class="px-4" method="POST" >
                @csrf

                <!-- Product's name -->
                <div class="mb-3">
                    <label for="name" class="form-label">نام کاربر<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="name" placeholder="نام کاربر" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Price and off -->
                <div class="row mt-3">
                    <!-- phone -->
                    <div class="col-md-4 mb-3">
                        <label for="mobile" class="form-label"> شماره موبایل<span class="text-danger">*</span> :</label>
                        <input type="text" class="form-control" id="mobile" placeholder="شماره موبایل" name="mobile"
                            value="{{ old('mobile') }}">
                        @error('mobile')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- password --}}
                    <div class="col-md-4 mb-2">
                        <label for="password" class="form-label">رمز ورود<span class="text-danger">*</span> :</label>
                        <input type="password" class="form-control" id="password" placeholder="حداقل 8 رقم" name="password"
                            value="{{ old('password') }}">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- email -->

                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">ایمیل :</label>
                        <input type="email" class="form-control" id="email" placeholder="example@gmail.com" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- <!-- image -->
                    <div class="col-md-4 mb-3">
                        <label for="image" class="form-label">عکس پرسنلی :</label>
                        <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit"
                        class="btn submit-btn w-25 border border-3 border-dark align-middle rounded-pill shadow">ذخیره</button>
                        <p class="form-p mt-2 text-white" style="display: none">
                            <input class="form-check-input" type="checkbox" name="rules" value="1" required checked>
                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#rulesModal"
                                style="cursor: pointer;">
                                ورود شما به معنای پذیرش شرایط سایت و قوانین حریم خصوصی است
                            </span>
                            <span class="text-danger">*</span>
                        </p>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('javaScript')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#birthDate").persianDatepicker({
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
@endsection