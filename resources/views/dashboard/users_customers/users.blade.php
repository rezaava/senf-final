@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>کاربران</title>
@endsection
@section('onvan')
کاربران
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">کاربران</h5>
                <!-- <a href="#" class="btn btn-danger float-start">افزودن کاربر جدید</a> -->
            </div>
            <div class="table-responsive-sm">
                    <table class="table mt-2 text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام</th>
                                <th>شماره تلفن</th>
                                <th>ایمیل</th>
                                <th>نقش</th>
                                {{-- <th>تاریخ ثبت‌نام</th>
                                <th>باقی مانده اشتراک</th>
                                <th>سطح</th>
                                <th>حضور و غیاب</th> --}}
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td class="align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->mobile }}</td>
                                    <td class="align-middle">{{ $user->email ?? '__' }}</td>
                                    <td class="align-middle text-primary">
                                        {{ $user->roles->first()->display_name ?? 'بدون نقش' }}</td>
                                    {{-- <td class="align-middle">
                                        {{ Jalalian::forge($user->created_at)->format('%A, %d %B %y') }}
                                    </td>
                                    <td class="align-middle">{{ Carbon::now()->diffInDays($user->plan_end_date, false) }}
                                        روز
                                    </td>
                                    @if ($user->level)
                                        @if ($user->level == 1)
                                            <td class="align-middle text-success">تازه کار</td>
                                        @elseif($user->level == 2)
                                            <td class="align-middle text-primary">پیشرفته</td>
                                        @elseif($user->level == 3)
                                            <td class="align-middle text-danger">حرفه ای</td>
                                        @endif
                                    @else
                                        <td class="align-middle text-danger">تعیین سطح نشده</td>
                                    @endif
                                    <td class="align-middle">
                                        <a href="{{ route('absence', ['id' => $user->id]) }}" class="text-primary">حضور و
                                            غیاب</a>
                                    </td> --}}
                                    <td class="align-middle">
                                        <a href="{{ route('user.edit', ['id' => $user->id]) }}"
                                            class="text-success mx-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="{{ route('user.delete', ['id' => $user->id]) }}" class="text-danger mx-1"><i
                                                class="fa-regular fa-trash-can"></i></a>
                                        <a href="#"
                                            class="text-primary mx-1"><i class="fa-solid fa-eye"></i></a>
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
