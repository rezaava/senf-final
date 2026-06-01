@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>درخواست ها</title>
@endsection
@section('onvan')
لیست درخواست ها   
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-start m-2">لیست درخواست ها</h5>
                <!-- <a href="#" class="btn btn-danger float-start">افزودن کاربر جدید</a> -->
            </div>
            <div class="table-responsive w-100">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>توضیحات</th>
                            <th>کاربر</th>
                            <th>تاریخ ثبت</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $key => $request)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $request->title }}</td>
                                <td class="align-middle">{{ $request->description }}</td>
                                <td class="align-middle">{{ $request->user->name ?? '__' }}</td>
                                <td class="align-middle">
                                    {{ Jalalian::forge($request->created_at)->format('Y/m/d') }}
                                </td>
                                @if ($request->status == 0)
                                    <td class="align-middle text-warning">در انتظار</td>
                                @elseif($request->status == 1)
                                    <td class="align-middle text-success">تایید شده</td>
                                @elseif($request->status == 2)
                                    <td class="align-middle text-danger">رد شده</td>
                                @endif
                                <td class="align-middle">
                                    @if (!Auth::user()->hasRole('user'))
                                        <a href="{{ route('request.show', ['id' => $request->id]) }}"
                                            class="btn btn-primary mx-1">
                                            مشاهده درخواست
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
