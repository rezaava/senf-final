@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>{{ $request->title }}</title>
@endsection
@section('onvan')
  اطلاعات کاربر 
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- کاربر -->
        <div class="row g-0 mt-4 p-2 px-4 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <h4 class="p-3">{{ $request->title }}</h4>
                <a href="/dashboard/request" class="btn btn-outline-danger">انصراف</a>
            </div>
            <div class="clearfix mt-2">
                <h5 class="float-end">اطلاعات کاربر</h5>
                <!-- <a href="#" class="btn btn-danger float-start">افزودن کاربر جدید</a> -->
            </div>
            <div class="table-responsive w-100">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>شماره موبایل</th>
                            <th>کد ملی</th>
                            <th>تاریخ تولد</th>
                            <th>تاریخ ثبت نام</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle">{{ $request->user->name }}</td>
                            <td class="align-middle">{{ $request->user->mobile }}</td>
                            <td class="align-middle">{{ $request->user->meliCode }}</td>
                            <td class="align-middle">
                                {{ Jalalian::forge($request->user->birthDate)->format('Y/m/d') }}
                            </td>
                            <td class="align-middle">
                                {{ Jalalian::forge($request->user->created_at)->format('Y/m/d') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- سالن --}}
        <div class="row g-0 mt-4 p-2 px-4 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">اطلاعات صنف</h5>
                <!-- <a href="#" class="btn btn-danger float-start">افزودن کاربر جدید</a> -->
            </div>
            <div class="table-responsive w-100">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>عکس</th>
                            <th>نام</th>
                            <th>توضیحات</th>
                            <th>تلفن</th>
                            <th>موبایل</th>
                            <th>کد پستی</th>
                            <th>آدرس</th>
                            <th>تاریخ ثبت</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($request->organ)
                            <tr>
                                <td class="align-middle">
                                    <img src="{{ asset($request->organ->name) }}" alt="لوگو" width="80px">
                                </td>
                                <td class="align-middle">{{ $request->organ->name }}</td>
                                <td class="align-middle">{{ $request->organ->description }}</td>
                                <td class="align-middle">{{ $request->organ->phone }}</td>
                                <td class="align-middle">{{ $request->organ->mobile }}</td>
                                <td class="align-middle">{{ $request->organ->postalCode }}</td>
                                <td class="align-middle">{{ $request->organ->address }}</td>
                                <td class="align-middle">
                                    {{ Jalalian::forge($request->organ->RegistrationDate)->format('Y/m/d') }}
                                </td>
                                @if ($request->organ->status == 0)
                                    <td class="align-middle text-warning">در انتظار</td>
                                @elseif($request->organ->status == 1)
                                    <td class="align-middle text-success">فعال</td>
                                @elseif($request->organ->status == 2)
                                    <td class="align-middle text-danger">غیر فعال</td>
                                @endif
                            </tr>
                        @else
                            <tr>
                                <td class="align-middle">
                                    <img src="{{ asset($request->requestable->name) }}" alt="لوگو" width="80px">
                                </td>
                                <td class="align-middle">{{ $request->requestable->name }}</td>
                                <td class="align-middle">{{ $request->requestable->description }}</td>
                                <td class="align-middle">{{ $request->requestable->phone }}</td>
                                <td class="align-middle">{{ $request->requestable->mobile }}</td>
                                <td class="align-middle">{{ $request->requestable->postalCode }}</td>
                                <td class="align-middle">{{ $request->requestable->address }}</td>
                                <td class="align-middle">
                                    {{ Jalalian::forge($request->requestable->RegistrationDate)->format('Y/m/d') }}
                                </td>
                                @if ($request->status == 0)
                                    <td class="align-middle text-warning">در انتظار</td>
                                @elseif($request->status == 1)
                                    <td class="align-middle text-success">فعال</td>
                                @elseif($request->status == 2)
                                    <td class="align-middle text-danger">غیر فعال</td>
                                @endif
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row g-0 mt-4 p-2 px-4 rounded-4 shadow bg-white">
            <div class="mt-2">
                <h5 class="">قرارداد انتخابی</h5>
                <p>
                    <strong>{{$contract_template->title}}</strong><br>
                    <span>{{$contract_template->text}}</span>
                </p>
                <!-- <a href="#" class="btn btn-danger float-start">افزودن کاربر جدید</a> -->
            </div>
        </div>
        @if ($request->status != 1)
            <div class="row g-0 mt-4 p-2 px-4 rounded-4 shadow bg-white">
                <form action="{{ route('request.status', ['id' => $request->id]) }}" method="post">
                    @csrf
                    <label for="reject">دلیل رد درخواست</label>
                    <textarea name="reject" id="reject" class="form-control"></textarea>
                    <p class="text-danger">
                        <small>
                            در صورت رد درخواست پر کردن فیلد دلیل رد درخواست الزامی می‌باشد
                        </small>
                    </p>
                    <button type="submit" name="status" value="0" class="btn btn-outline-danger mt-2">رد
                        درخواست</button>
                    <button type="submit" name="status" value="1" class="btn btn-outline-success mt-2">تایید
                        درخواست</button>
                </form>
            </div>
        @endif
    </div>
@endsection
@section('javaScript')
@endsection
