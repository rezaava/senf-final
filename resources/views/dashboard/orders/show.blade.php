@extends('dashboard.layout.master')
@section('onvan')
جزئیات سفارش
@endsection
@section('title')
    <title>سفارشات</title>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    <div class="col pb-5">
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="p-3">جزئیات سفارش</h4>

            <div class="card p-4 mb-4">
                <p><strong>اپراتور:</strong> {{ $reservation->operator->name ?? '---' }}</p>
                <p><strong>مشتری:</strong> {{ $reservation->costumer->name ?? '---' }}</p>
                <p><strong>خدمت:</strong> {{ $reservation->services()->first()->service->name }}</p>
                <p><strong>شروع :</strong> {{ Jdate($reservation->start_at)->format('%A, %d %B , ساعت H:i') }}</p>
                <p><strong>پایان :</strong> {{ Jdate($reservation->end_at)->format('%A, %d %B , ساعت H:i') }}</p>
            </div>

            @if (!$hasPriceRange)
                {{-- سناریو 1: بدون بازه قیمتی --}}
                <div class="text-info">
                    این خدمت دارای قیمت ثابت است.
                </div>
                <p><strong>مبلغ پرداخت‌شده:</strong> {{ number_format($reservation->total_price) }} تومان</p>
            @elseif($reservation->media)
                {{-- سناریو 2: عکس آپلود شده --}}
                <div class="text-warning">
                    تصویر توسط کاربر ارسال شده است. لطفاً قیمت پیشنهادی را وارد کنید.
                </div>

                <div class="mb-3">
                    <img src="{{ asset($reservation->media->path) }}" class="img-fluid rounded mb-3"
                        style="max-width: 200px;">
                </div>

                {{-- <form action="{{ route('appointments.suggest_price', $appointment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="price" class="form-label">قیمت پیشنهادی (تومان)</label>
                        <input type="number" name="suggested_price" class="form-control" required
                            min="{{ $appointment->service->price_min }}" max="{{ $appointment->service->price_max }}">
                    </div>
                    <button type="submit" class="btn btn-primary">ارسال پیشنهاد</button>
                </form> --}}
            @else
                {{-- سناریو 3: بدون عکس، پرداخت در محل --}}
                <div class="text-info">
                    کاربر تصویر ارسال نکرده است. مبلغ پایه پرداخت شده و باقی‌مانده در محل پرداخت شده است.
                </div>

                {{-- <form action="{{ route('appointments.finalize_price', $appointment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="final_price" class="form-label">مبلغ نهایی پرداخت‌شده توسط کاربر (تومان)</label>
                        <input type="number" name="final_price" class="form-control" value="{{ $appointment->price }}" {{$appointment->price > 0 ? "disabled" : ''}} required>
                    </div>
                    <button type="submit" class="btn btn-success">ثبت نهایی</button>
                </form> --}}
            @endif
        </div>
    </div>
@endsection
