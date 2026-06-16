@extends('web.layouts.master')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset/css/cart.css') }}">
    <style>
        .payment-card {
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: .3s;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            transition: all 0.25s ease-in-out;
            border: 2px solid transparent;
        }

        .active-card{
            border-color: var(--color-primary);
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .modal-content {
            border-radius: 20px;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #0d6efd;
            color: white;
            font-weight: 600;
        }
    </style>
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
                <button class="checkout-btn" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="bi bi-wallet2 me-2"></i>
                    پرداخت و نهایی‌سازی
                </button>
            </div>

            <!-- Modal Pay -->
            <div class="modal fade" id="paymentModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                    
                        <div class="modal-header">
                            <h5 class="modal-title">
                                انتخاب روش پرداخت
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                    
                        <div class="modal-body">
                            <div class="row g-3">
                            
                                <!-- کیف پول -->
                                <div class="col-6">
                                    <div class="card payment-card h-100">
                                        <div class="card-body text-center">
                                            <i class="bi bi-wallet2 display-5 text-primary"></i>
                                            <h6 class="mt-3 fw-bold">کیف پول</h6>
                                            <small class="text-muted">
                                                پرداخت از موجودی
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- کارت بانکی -->
                                <div class="col-6">
                                    <div class="card payment-card h-100">
                                        <div class="card-body text-center">
                                            <i class="bi bi-credit-card-2-front display-5 text-success"></i>
                                            <h6 class="mt-3 fw-bold">کارت بانکی</h6>
                                            <small class="text-muted">
                                                پرداخت آنلاین
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    
                        <div class="modal-footer">
                            <button class="btn btn-primary w-100 payFinally">
                                ادامه پرداخت
                            </button>
                        </div>
                    
                    </div>
                </div>
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

        const modalElement = document.getElementById('paymentModal');
        const paymentModal = bootstrap.Modal.getOrCreateInstance(modalElement);

        const cards = document.querySelectorAll('.payment-card');

        cards.forEach((card)=>{
            card.addEventListener('click' , function(){
                cards.forEach((card)=>{
                    card.classList.remove('active-card')
                })
                card.classList.add('active-card')
            })
        })

        document.querySelector('.payFinally').addEventListener('click', function() {
            const itemCount = document.querySelectorAll('.cart-item').length;

            if (itemCount > 0) {
                alert(`پرداخت با موفقیت انجام شد! ${itemCount} نوبت رزرو شده پرداخت و نهایی شد.`);
                paymentModal.hide();
                
            } else {
                alert('سبد خرید شما خالی است!');
            }
        });
    </script>
@endsection
