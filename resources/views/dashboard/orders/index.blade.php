@extends('dashboard.layout.master')
@section('onvan')
سفارشات
@endsection
@section('title')
    <title>سفارشات</title>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    <div class="col pb-5">
        {{-- سفارش ها --}}
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="pt-3 px-4">سفارش ها</h4>
            <!-- charts -->
            <div class="col-md-12 my-auto p-3">
                <div class="table-responsive-sm">
                    <table class="table mt-2 text-center">
                        <thead>
                            <tr>
                                <th>خدمت</th>
                                <th>اپراتور</th>
                                <th>مشتری</th>
                                <th>تاریخ و زمان شروع</th>
                                <th>تاریخ و زمان پایان</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key => $appointment)
                                <tr>
                                    <td class="align-middle">{{ $appointment->services()->first()->service->name }}</td>
                                    <td class="align-middle">{{ $appointment->operator->name }}</td>
                                    <td class="align-middle">{{ $appointment->costumer->name }}</td>
                                    <td class="align-middle">{{ Jdate($appointment->start_at)->format('%A, %d %B , ساعت H:i') }}</td>
                                    <td class="align-middle">{{ Jdate($appointment->end_at)->format('%A, %d %B , ساعت H:i') }}</td>
                                    @if ($appointment->status == 'paid')
                                        <td class="align-middle text-success">پرداخت شده</td>
                                    @else
                                        <td class="align-middle text-success">انجام شده</td>
                                    @endif
                                    <td class="align-middle">
                                        <a href="{{ route('orders.show', ['reservation' => $appointment]) }}"
                                            class="text-primary mx-1">مشاهده <i class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
