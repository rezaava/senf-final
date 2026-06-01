@extends('dashboard.layout.master')
@section('onvan')
پروفایل
@endsection
@section('title')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    <!-- main  -->
    <div class="col pb-5">
        <!-- info -->
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <div class="col-md-2 p-2 text-center rounded-4 border">
                <img src="{{ asset($organ->image ?? 'files/user.svg') }}" alt="profile" class="w-100"
                    style="max-width: 120px;">
            </div>
            <div class="col-md-10 p-2 rounded-4 border">
                {{-- اطلاعات --}}
                <div class="row p-2 m-2">
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3">نام سالن :</h5>
                            <p class="">{{ $organ->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3">مدیر سالن :</h5>
                            <p class="">{{ $organ->Manager->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3">وضعیت سالن :</h5>
                            @if ($organ->status == 0)
                                <p class="text-warning">در انتظار تایید</p>
                            @elseif ($organ->status == 1)
                                <p class="text-success">فعال</p>
                            @else
                                <p class="text-danger">غیر فعال</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> شماره موبایل :</h5>
                            <p class="">{{ $organ->mobile }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> شماره تلفن :</h5>
                            <p class="">{{ $organ->phone }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> ایمیل :</h5>
                            <p class="">{{ $organ->email ?? '--' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> کد پستی :</h5>
                            <p class="">{{ $organ->postalCode }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <div class="d-flex">
                            <h5 class="ms-3"> تاریخ عضویت :</h5>
                            <p class="">
                                {{ Morilog\Jalali\Jalalian::forge($organ->created_at)->format('%d %B ، %Y') }}
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

                    {{-- <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mt-2" data-bs-toggle="modal"
                            data-bs-target="#sans">ثبت سانس</button>
                    </div> --}}
                </div>
            </div>
        </div>
        {{-- امار نوبت ها --}}
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="pt-3 px-4">آمار نوبت ها</h4>
            <!-- charts -->
            <div class="col-md-12 my-auto p-3">
                <div class="rounded-4 bg-white p-2">
                    <canvas id="linechart" style="width:100%;height:400px"></canvas>
                </div>
            </div>
        </div>
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
                                <th>تاریخ نوبت</th>
                                <th>زمان نوبت</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">خدمت تست</td>
                                <td class="align-middle">اپراتور فلانی</td>
                                <td class="align-middle">مشتری</td>
                                <td class="align-middle">403/3/22</td>
                                <td class="align-middle">08:00 - 10:00</td>
                                <td class="align-middle text-success">رزرو شده</td>
                                <td class="align-middle">
                                    <a href="#" class="text-success mx-1"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="#" class="text-danger mx-1"><i
                                            class="fa-regular fa-trash-can"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        {{-- تراکنش ها --}}
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="pt-3 px-4">تراکنش ها</h4>
            <!-- charts -->
            <div class="col-md-12 my-auto p-3">
                <div class="table-responsive-sm">
                    <table class="table table-bordered mt-2 text-center">
                        <thead>
                            <tr class="bg-secondary">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th colspan="2">مانده بر خط</th>
                            </tr>
                            <tr>
                                <th>شناسه</th>
                                <th>تاریخ</th>
                                <th>ساعت</th>
                                <th>توضیحات</th>
                                <th>بدهکار</th>
                                <th>بستانکار</th>
                                <th>بدهکار</th>
                                <th>بستانکار</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">1234</td>
                                <td class="align-middle">403/3/22</td>
                                <td class="align-middle">08:05:12</td>
                                <td class="align-middle">مبلغ 20000 تومان بابت خرید فلانی با شماره شناسه 49618 به کیف پول واریز شد.</td>
                                <td class="align-middle">20,000</td>
                                <td class="align-middle">0</td>
                                <td class="align-middle">120,000</td>
                                <td class="align-middle"></td>
                            </tr>
                            <tr>
                                <td class="align-middle">1234</td>
                                <td class="align-middle">403/3/22</td>
                                <td class="align-middle">08:05:12</td>
                                <td class="align-middle">مبلغ 20000 تومان بابت حقوق ارایشگر فلانی با شماره شناسه 6 از کیف پول کسر شد.</td>
                                <td class="align-middle">0</td>
                                <td class="align-middle">20,000-</td>
                                <td class="align-middle">100,000</td>
                                <td class="align-middle"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javaScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        new Chart("linechart", {
            type: 'line',
            data: {
                labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
                datasets: [{
                    label: 'نمودار خطی',
                    data: [10, 20, 15, 25, 30, 20],
                    borderColor: 'rgb(54, 162, 235)', // رنگ خط
                    borderWidth: 2,
                    fill: false // جلوگیری از رنگی شدن زیر نمودار
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: ['label1', 'label2', 'label3', 'label4', 'label5', 'label6'],
                datasets: [{
                    data: ['1', '2', '3', '4', '5', '6'],
                    label: 'مراجع',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(54, 162, 235)',
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "مراجعین در ماه های اخیر",
                    fontColor: "#000",
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: "#000", // this here
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: "#000", // this here
                            beginAtZero: true,

                        },
                    }],
                },
            }
        });
    </script>
@endsection
