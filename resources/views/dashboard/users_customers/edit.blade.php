@extends('dashboard.layout.master')
@section('onvan')
ویرایش کاربر
@endsection
@section('title')
    <title>users</title>
    <style>
        .submit-btn {
            background-color: #003652;
            color: #ffffff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
@endsection

@section('body')
    <!-- main  -->
    <div class="col">
        <!-- Form -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white pb-3">
            <div class="clearfix mt-2">
                <h5 class="float-end">ویرایش کاربر</h5>
            </div>
            <form action="{{ route('user.editPost', ['id' => $user->id]) }}" class="px-4" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @CSRF
                <!-- Product's name -->
                <div class="mb-3">
                    <label for="name" class="form-label">نام کاربر<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="name" placeholder="نام کاربر" name="name"
                        value="{{ old('name') ?? $user->name }}">
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
                            value="{{ old('mobile') ?? $user->mobile }}">
                        @error('mobile')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- password --}}
                    <div class="col-md-4 mb-2">
                        <label for="password" class="form-label">رمز ورود جدید :</label>
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
                            value="{{ old('email') ?? $user->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit"
                class="btn submit-btn w-25 border border-3 border-dark align-middle rounded-pill shadow">ذخیره</button>
        </div>
        </form>
    </div>
    </div>
@endsection
@section('javaScript')
    <script>
        $(document).ready(function () {
            $("#birthDate").persianDatepicker({
                format: "YYYY/MM/DD",
                autoClose: true,
                autoFill: false,
                toolbox: {
                    calendarSwitch: {
                        enabled: true // نمایش دکمه تغییر تقویم
                    }
                }
            });
        });
    </script>
@endsection