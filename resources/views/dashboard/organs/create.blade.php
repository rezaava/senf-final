@extends('dashboard.layout.master')
@section('onvan')
صنف جدید
@endsection
@section('title')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

    <title>صنف جدید</title>
    <style>
        .submit-btn {
            background-color: #003652;
            color: #ffffff;
        }
    </style>
@endsection

@section('body')
    <!-- main  -->
    <div class="col px-3">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <!-- Form -->
        <div class="row g-0 mt-4 p-3 rounded-4 shadow bg-white pb-3">
            <div class="clearfix mt-2">
                <h5 class="float-end">صنف جدید</h5>
            </div>
            <form action="{{ route('organ.store') }}" class="px-4 mt-4" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">نام صنف<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="name" placeholder="نام صنف" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- Price and off -->
                <div class="row mt-3">
                    <!-- category -->
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">حوزه صنف <span class="text-danger">*</span> :</label>
                        <select name="category" id="category" class="form-select">
                            <option value="" selected disabled>انتخاب کنید</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- phone -->
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">شماره تلفن <span class="text-danger">*</span> :</label>
                        <input type="text" class="form-control" id="phone" placeholder="شماره تلفن" name="phone"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- password --}}
                    <div class="col-md-4 mb-3">
                        <label for="mobile" class="form-label">شماره موبایل <span class="text-danger">*</span> :</label>
                        <input type="text" class="form-control" id="mobile" placeholder="شماره موبایل" name="mobile"
                            value="{{ old('mobile') }}">
                        @error('mobile')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- email -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">ایمیل :</label>
                        <input type="email" class="form-control" id="email" placeholder="example@gmail.com"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- postal code -->
                    <div class="col-md-4 mb-3">
                        <label for="postalCode" class="form-label">کد پستی :</label>
                        <input type="number" class="form-control" id="postalCode" placeholder="کد پستی 10 رقمی"
                            name="postalCode" value="{{ old('postalCode') }}">
                        @error('postalCode')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- date -->
                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">تاریخ ثبت :</label>
                        <input type="text" class="form-control" id="date" placeholder="از روی تقویم انتخاب کنید"
                            name="date" value="{{ old('date') }}">
                        @error('date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- description --}}
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">توضیحات :</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- address --}}
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">آدرس <span class="text-danger">*</span>:</label>
                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- image -->
                    <div class="col-md-4 mb-3">
                        <label for="image" class="form-label">عکس/لوگوی صنف :</label>
                        <input type="file" class="form-control" id="image" name="image"
                            value="{{ old('image') }}">
                        @error('image')
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
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
@endsection
