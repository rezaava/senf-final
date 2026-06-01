@extends('dashboard.layout.master')
@section('onvan')
تکمیل پروفایل
@endsection
@section('title')
    <title>پروفایل</title>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    @if ($user->id == Auth::user()->id)
        <div class="modal modal-lg fade" id="edit">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header border-0 pb-0 mb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body pt-0"><br>
                        <div class="clearfix">
                            <h5 class="float-end">تکمیل پروفایل</h5>
                        </div>
                        <form action="{{ route('user.profile.update', ['id' => $user->id]) }}" class="px-4" method="POST"
                            autocomplete="off" enctype="multipart/form-data">
                            @CSRF
                            <!-- Product's name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">نام کاربر<span class="text-danger">*</span>
                                    :</label>
                                <input type="text" class="form-control" id="name" placeholder="نام کاربر"
                                    name="name" value="{{ old('name') ?? $user->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Price and off -->
                            <div class="row mt-3">
                                <!-- phone -->
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="form-label"> شماره موبایل<span class="text-danger">*</span>
                                        :</label>
                                    <input type="text" class="form-control" id="mobile" placeholder="شماره موبایل"
                                        name="mobile" value="{{ old('mobile') ?? $user->mobile }}">
                                    @error('mobile')
                                        <small class="text-danger">{{$message }}</small>
                                    @enderror
                                </div>
                                <!-- email -->
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">ایمیل :</label>
                                    <input type="email" class="form-control" id="email"
                                        placeholder="example@gmail.com" name="email"
                                        value="{{ old('email') ?? $user->email }}">
                                    @error('email')
                                        <small class="text-danger">{{$message }}</small>
                                    @enderror
                                </div>
                                <!-- meliCode -->
                                <div class="col-md-4 mb-3">
                                    <label for="meliCode" class="form-label">کد ملی :</label>
                                    <input type="number" class="form-control" id="meliCode" placeholder="10 رقمی"
                                        name="meliCode" value="{{ old('meliCode') ?? $user->meliCode }}">
                                    @error('meliCode')
                                        <small class="text-danger">{{$message }}</small>
                                    @enderror
                                </div>
                                <!-- email -->
                                <div class="col-md-4 mb-3">
                                    <label for="birthDate" class="form-label">تاریخ تولد :</label>
                                    <input type="text" class="form-control" id="birthDate"
                                        placeholder="روی تقدیم انتخاب کنید" name="birthDate"
                                        value="{{ old('birthDate') ?? $user->birthDate }}">
                                    @error('birthDate')
                                        <small class="text-danger">{{$message }}</small>
                                    @enderror
                                </div>
                                {{-- password --}}
                                <div class="col-md-4 mb-2">
                                    <label for="password" class="form-label">رمز ورود جدید :</label>
                                    <input type="password" class="form-control" id="password" placeholder="حداقل 8 رقم"
                                        name="password" value="{{ old('password') }}">
                                    @error('password')
                                        <small class="text-danger">{{$message }}</small>
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
            </div>
        </div>
    @endif

    <!-- main  -->
    <div class="col pb-5">
        <!-- info -->
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <div class="col-md-2 p-2 text-center rounded-4 border">
                <img src="{{ asset($user->image ?? 'files/user.svg') }}" alt="profile" class="w-100"
                    style="max-width: 120px;">
            </div>
            <div class="col-md-10 p-2 rounded-4 border">
                {{-- اطلاعات --}}
                <div class="row p-2 m-2">
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3">نام کاربر :</h5>
                            <p class="">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> شماره موبایل :</h5>
                            <p class="">{{ $user->mobile }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> کد ملی :</h5>
                            <p class="">{{ $user->meliCode }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> ایمیل :</h5>
                            <p class="">{{ $user->email ?? '--' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> تاریخ عضویت :</h5>
                            <p class="">
                                {{ Morilog\Jalali\Jalalian::forge($user->created_at)->format('%d %B ، %Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                {{-- گزینه ها --}}
                <div class="row p-2">
                    {{-- <div class="col-md-3">
                        <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                            class="btn btn-secondary w-100 mt-2">ویرایش</a>
                    </div> --}}
                    @if ($user->id == Auth::user()->id)
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info w-100 mt-2" data-bs-toggle="modal"
                                data-bs-target="#edit">تکمیل پروفایل</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('logout') }}" class="btn btn-danger w-100 mt-2">خروج</a>
                        </div>
                    @endif

                    {{-- <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mt-2" data-bs-toggle="modal"
                            data-bs-target="#sans">ثبت سانس</button>
                    </div> --}}
                </div>
            </div>
        </div>

    </div>
@endsection
@section('javaScript')
    <script>
        $(document).ready(function() {
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
