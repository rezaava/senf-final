@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>اپراتور ها</title>
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
                {{-- <a href="{{route('user.create')}}" class="btn btn-danger float-start">اپراتور جدید</a> --}}
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>شماره تلفن</th>
                            <th>نقش کاربر</th>
                            <th>ایمیل</th>
                            <th>کد ملی</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $user->name }}</td>
                                <td class="align-middle">{{ $user->mobile }}</td>
                                <td class="align-middle">{{ $user->roles()->first()?->display_name ?? 'بدون نقش' }}</td>
                                <td class="align-middle">{{ $user->email ?? '__' }}</td>
                                <td class="align-middle">{{ $user->meliCode ?? '__' }}</td>
                                @if ($user->pivot and $user->pivot->status)
                                    @if ($user->pivot->status == 0)
                                        <td class="align-middle text-warning">در انتظار تایید</td>
                                    @elseif($user->pivot->status == 1)
                                        <td class="align-middle text-success">تایید شده</td>
                                    @elseif($user->pivot->status == 2)
                                        <td class="align-middle text-danger">رد شده</td>
                                    @endif
                                @else
                                    <td class="align-middle text-danger">--</td>
                                @endif
                                <td class="align-middle">
                                    {{-- <a href="{{ route('user.edit', ['id' => $user->id]) }}"
                                            class="text-success mx-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="{{ route('user.delete', ['id' => $user->id]) }}" class="text-danger mx-1"><i
                                                class="fa-regular fa-trash-can"></i></a> --}}
                                    @if ($user->hasRole('operator'))
                                        <a href="{{ route('work-hour.index', $user->id) }}" class="btn btn-outline-success btn-sm mx-1">
                                            <small>ساعات کاری</small>
                                        </a>
                                    @endif
                                    <a href="{{ route('user.profile', ['id' => $user->id]) }}" class="btn btn-outline-primary btn-sm mx-1"><i
                                            class="fa-solid fa-eye"></i></a>
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
