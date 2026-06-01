@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/cart.css') }}">
@endsection
@section('content')
    <!-- هدر -->
    <div class="header">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="back-btn">
                <i class="bi bi-arrow-right"></i>
            </a>
            <h1>سبد خرید نوبت‌ها</h1>
            <div style="width: 24px;"></div> <!-- برای بالانس کردن layout -->
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container mt-3 px-3" style="padding-bottom: 140px;">
        @if ($cart != null)
            <!-- لیست نوبت‌های رزرو شده -->
            <div id="cartItems">
                @foreach ($cart->reservations as $reservation)
                    <div class="cart-item position-relative" id="cart-item-{{ $reservation->id }}">
                        <div class="row">
                            <div class="col-12">
                                <div class="service-name">{{ $reservation->services->first()->name }}</div>
                                <div class="salon-name">{{ $reservation->organ->name }}</div>

                                <div class="stylist-info">
                                    <div class="stylist-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="stylist-name">{{ $reservation->operator->name }}</div>
                                </div>

                                <div class="time-details mb-3">
                                    <div class="row">
                                        <div class="col">
                                            <div class="time-label">روز و زمان شروع:</div>
                                            <div class="time-value">
                                                {{ Jdate($reservation->start_at)->format('%A, %d %B %y ساعت H:i') }}</div>
                                            <span>الی</span>
                                            <div class="time-value">
                                                {{ Jdate($reservation->end_at)->format('%A, %d %B %y ساعت H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <button class="remove-btn" data-id="{{ $reservation->id }}">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">مبلغ :</span>
                                    <div class="price">{{ number_format($reservation->total_price) }} تومان</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- پیام سبد خرید خالی (در صورت نیاز) -->
            <div id="emptyCartMessage" class="d-none">
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h4>سبد خرید شما خالی است</h4>
                    <p class="mt-2">هنوز هیچ نوبتی رزرو نکرده‌اید.</p>
                </div>
            </div>

            <!-- بخش پایینی فیکس شده برای قیمت و دکمه خرید -->
            <div class="checkout-footer">
                <div class="row align-items-center mb-2">
                    <div class="col-6">
                        <span class="text-muted">قابل پرداخت:</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="total-price">{{ number_format($cart->reservations->sum('total_price')) }} تومان</span>
                    </div>
                </div>
                <button class="checkout-btn">
                    <i class="bi bi-wallet2 me-2"></i>
                    پرداخت و نهایی‌سازی
                </button>
            </div>
        @else
            <!-- پیام سبد خرید خالی (در صورت نیاز) -->
            <div id="emptyCartMessage" class="">
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h4>سبد خرید شما خالی است</h4>
                    <p class="mt-2">هنوز هیچ نوبتی رزرو نکرده‌اید.</p>
                </div>
            </div>
        @endif

    </div>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function() {

                const reservationId = this.dataset.id;
                const cartItem = document.getElementById('cart-item-' + reservationId);

                fetch(`/cart/remove/${reservationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {

                            cartItem.style.opacity = '0.5';

                            setTimeout(() => {
                                cartItem.remove();
                                updateTotalPrice();

                                $("#cart-badge").text(document.querySelectorAll('.cart-item')
                                    .length);
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    document.getElementById('cartItems').classList.add(
                                        'd-none');
                                    document.getElementById('emptyCartMessage').classList
                                        .remove('d-none');
                                }

                            }, 300);

                        } else {
                            alert('خطا در حذف رزرو');
                        }

                    })
                    .catch(() => {
                        alert('مشکلی پیش آمد');
                    });

            });
        });

        // تابع برای بروزرسانی قیمت کل
        function updateTotalPrice() {
            let total = 0;

            document.querySelectorAll('.price').forEach(priceElement => {
                const priceText = priceElement.textContent.replace(/[^\d]/g, '');
                const price = parseInt(priceText) || 0;
                total += price;
            });

            // فرمت کردن قیمت برای نمایش
            const formattedTotal = total.toLocaleString('fa-IR') + ' تومان';
            document.querySelector('.total-price').textContent = formattedTotal;
        }

        // شبیه‌سازی کلیک روی دکمه پرداخت
        document.querySelector('.checkout-btn').addEventListener('click', function() {
            const itemCount = document.querySelectorAll('.cart-item').length;

            if (itemCount > 0) {
                alert(`پرداخت با موفقیت انجام شد! ${itemCount} نوبت رزرو شده پرداخت و نهایی شد.`);
                // در اینجا کد واقعی پرداخت را اضافه کنید
            } else {
                alert('سبد خرید شما خالی است!');
            }
        });
    </script>
@endsection
