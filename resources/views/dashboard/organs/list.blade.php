@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>لیست سالن ها</title>
@endsection
@section('onvan')
لیست سالن ها
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">لیست سالن ها</h5>
                {{-- <a href="#" class="btn btn-danger float-start">صنف جدید</a> --}}
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>شهر</th>
                            <th>مدیر سالن</th>
                            <th>وضعیت</th>
                            <th>وضعیت مالی</th>
                            <th>تاریخ پایان قرارداد</th>
                            <th>اپراتور ها</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organs as $key => $organ)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $organ->name }}</td>
                                <td class="align-middle">{{ $organ->city }}</td>
                                <td class="align-middle">{{ $organ->Manager->name ?? '' }}</td>
                                @if ($organ->status == 1)
                                    <td class="align-middle text-success">فعال</td>
                                @elseif($organ->status == 2)
                                    <td class="align-middle text-danger">غیر فعال</td>
                                @elseif($organ->status == 3)
                                    <td class="align-middle text-warning">در انتظار تایید</td>
                                @endif

                                <td class="align-middle">
                                    {{ number_format($organ->Manager->wallet) }} تومان
                                </td>
                                <td class="align-middle">
                                    @php
                                        $contract = $organ
                                            ->contracts()
                                            ->where('to_user_id', $organ->Manager->id)
                                            ->orderByDesc('start_date')
                                            ->first();

                                        $target = Carbon::parse($contract->end_date);
                                        $now = Carbon::now();

                                        $daysLeft = $now->diffInDays($target, false);
                                    @endphp
                                    {{ $daysLeft }} روز باقی مانده
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('user.list', ['id' => $organ->id]) }}"
                                        class="btn btn-outline-info">{{ $organ->operator()->whereHasRole('operator')->count() }} اپراتور</a>
                                </td>
                                <td class="align-middle">
                                    {{-- <a href="#" class="text-success mx-1"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="#" class="text-danger mx-1"><i
                                            class="fa-regular fa-trash-can"></i></a> --}}
                                    <a href="{{ route('organ.profile', ['organ' => $organ]) }}"
                                        class="btn btn-primary mx-1">
                                        <i class="fa-solid fa-eye"></i>
                                        پروفایل
                                    </a>
                                    <form action="{{ route('organ.status', ['organ' => $organ]) }}" method="post">
                                        @csrf
                                        @if ($organ->status == 1)
                                            <button class="btn btn-outline-danger" name="status" value="2">غیرفعال
                                                کردن</button>
                                        @elseif ($organ->status == 2)
                                            <button class="btn btn-outline-success" name="status" value="1">فعال
                                                کردن</button>
                                        @endif
                                    </form>
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
