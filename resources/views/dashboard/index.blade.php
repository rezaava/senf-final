@extends('dashboard.layout.master')
@section('onvan')
داشبورد مدیریت
@endsection
{{-- title --}}
@section('title')
    <title>داشبورد مدیریت</title>
@endsection

{{-- body --}}
@section('body')
    <div class="col px-4 pb-5">
        @if (Auth::user())
            @if (Auth::user()->hasRole(['operator']))
                <div class="row mt-4">
                    <!-- profile -->
                    <div class="col-md-4 my-auto p-3">
                        <div class="rounded-4 shadow bg-white p-2">
                            <div class="text-center">
                                <img src="{{ asset('files/user.svg') }}" alt="profile" width="80">
                                <p>{{ Auth::user()->name }}</p>
                                <ul class="list-group text-end mt-2 border px-0">
                                    <li class="list-group-item border-0 py-1">
                                        <strong>سالن :</strong> {{ Auth::user()->organSelected->name }}
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>کیف پول :</strong> {{ number_format(Auth::user()->wallet) }} <small>تومان</small>
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>تعداد رزرو ها :</strong>
                                        {{ Auth::user()->appointments()->where('status', 1)->count() }}
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>شماره موبایل :</strong> {{ Auth::user()->mobile }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 my-auto p-3">
                        <div class="rounded-4 shadow bg-white p-2">
                            <div class="clearfix mt-2">
                                <h5 class="float-end">نوبت های رزرو شده</h5>
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table mt-2 text-center">
                                    <thead>
                                        <tr>
                                            <th>خدمت</th>
                                            <th>مشتری</th>
                                            <th>تاریخ نوبت</th>
                                            <th>زمان نوبت</th>
                                            <th>وضعیت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->appointments()->where('status', 1)->get() as $key => $appointment)
                                            <tr>
                                                <td class="align-middle">{{ $appointment->service->name }}</td>
                                                <td class="align-middle">{{ $appointment->customer_name }}</td>
                                                <td class="align-middle">{{ jdate($appointment->day)->format('%d %B') }}
                                                </td>
                                                <td class="align-middle">{{ $appointment->start_time }} -
                                                    {{ $appointment->end_time }}
                                                </td>
                                                @if ($appointment->status == 1)
                                                    <td class="align-middle">رزرو شده</td>
                                                @else
                                                    <td class="align-middle">انجام شده</td>
                                                @endif
                                                <td class="align-middle">
                                                    <a href="{{ route('orders.show', ['appointment' => $appointment]) }}"
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
            @endif
            @if (Auth::user()->hasRole(['manager']))
                <div class="row mt-4">
                    <!-- profile -->
                    <div class="col-md-4 my-auto p-3">
                        <div class="rounded-4 shadow bg-white p-2">
                            <div class="text-center">
                                <img src="{{ asset('files/user.svg') }}" alt="profile" width="80">
                                <p>{{ Auth::user()->name }}</p>
                                <ul class="list-group text-end mt-2 border px-0">
                                    <li class="list-group-item border-0 py-1">
                                        <strong>سالن :</strong> {{ Auth::user()->organSelected->name }}
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>کیف پول :</strong> {{ number_format(Auth::user()->wallet) }} <small>تومان</small>
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>تعداد رزرو ها :</strong>
                                        {{ Auth::user()->organSelected->appointments()->where('status', 1)->count() }}
                                    </li>
                                    <li class="list-group-item border-0 py-1">
                                        <strong>شماره موبایل :</strong> {{ Auth::user()->mobile }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 my-auto p-3">
                        <div class="rounded-4 shadow bg-white p-2">
                            <div class="clearfix mt-2">
                                <h5 class="float-end">نوبت های رزرو شده</h5>
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table mt-2 text-center">
                                    <thead>
                                        <tr>
                                            <th>خدمت</th>
                                            <th>مشتری</th>
                                            <th>تاریخ نوبت</th>
                                            <th>زمان نوبت</th>
                                            <th>وضعیت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->organSelected->appointments()->where('status', 1)->get() as $key => $appointment)
                                            <tr>
                                                <td class="align-middle">{{ $appointment->service->name }}</td>
                                                <td class="align-middle">{{ $appointment->customer_name }}</td>
                                                <td class="align-middle">{{ jdate($appointment->day)->format('%d %B') }}
                                                </td>
                                                <td class="align-middle">{{ $appointment->start_time }} -
                                                    {{ $appointment->end_time }}
                                                </td>
                                                @if ($appointment->status == 1)
                                                    <td class="align-middle">رزرو شده</td>
                                                @else
                                                    <td class="align-middle">انجام شده</td>
                                                @endif
                                                <td class="align-middle">
                                                    <a href="{{ route('orders.show', ['appointment' => $appointment]) }}"
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
            @endif
            <!-- charts -->
            <div class="row mt-4">
                <div class="col-md-6 my-auto p-3">
                    <div class="rounded-4 shadow bg-white p-2">
                        <canvas id="myChart" class="" style="width:100%;max-height:600px;"></canvas>
                    </div>
                </div>
                <div class="col-md-6 my-auto p-4">
                    <div class="rounded-4 shadow bg-white p-2">
                        <canvas id="linechart" class="" style="width:100%;min-height:200px;max-height:100%"></canvas>
                    </div>
                </div>
            </div>
            <!-- charts -->
            <div class="row mt-4">
                <div class="col my-auto p-3">
                    <div class="rounded-4 shadow bg-white p-2 mx-4" >
                        <canvas id="myChart2" class="mx-auto" style="width:100%;max-width:300px"></canvas>
                    </div>
                </div>
                <div class="col my-auto p-3">
                    <div class="rounded-4 shadow bg-white p-2">
                        <canvas id="myChart3" class="mx-auto" style="width:100%;max-width:300px"></canvas>
                    </div>
                </div>
            </div>
            <!-- orders -->
        @endif
    </div>
@endsection


@section('javaScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>
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


        // const xValues = [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150];
        // const yValues = [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 15];
new Chart("linechart", {
    type: 'line',
    data: {
        labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
        datasets: [{
            label: 'نمودار خطی',
            data: [10, 20, 15, 25, 30, 20],
            borderColor: 'rgba(100, 200, 255, 1)',
            borderWidth: 2.5,
            pointBackgroundColor: 'rgba(100, 200, 255, 0.8)',
            pointBorderColor: '#fff',
            pointBorderWidth: 1.5,
            fill: true,
            pointRadius: 0,
            backgroundColor: 'rgba(100, 200, 255, 0.15)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            yAxes: [{                   
                ticks: {
                    min: 0,                 
                    max: 50,                
                    stepSize: 10,    
                    fontFamily: 'dana',  
                    fontSize:'10',        
                }
            }],
            xAxes: [{
                ticks: {
                    fontFamily: 'dana',     // فونت برای برچسب‌های پایین
                    fontSize: '10',
                }
            }],
            legend: {                           // فونت عنوان نمودار
            labels: {
                fontFamily: 'dana',
                fontSize: '10',
            }
        }
        }
    }
});
        var xValues1 = ["درآمد های جانبی", "درآمد از تراکنش ها"];
        var yValues1 = ['1000', '2000'];
        new Chart("myChart2", {
            type: "doughnut",
            data: {
                labels: xValues1,
                datasets: [{
                    label: 'My First Dataset',
                    data: yValues1,
                    backgroundColor: [
                        '#00FFAB',
                        '#14C38E',
                        '#00FF9C',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "نمودار کل درآمد ها"
                }
            }
        });


        new Chart("myChart3", {
            type: "doughnut",
            data: {
                labels: ['هزینه های جانبی'],
                datasets: [{
                    label: 'My First Dataset',
                    data: ['1000'],
                    backgroundColor: [
                        '#F72C5B',
                        'red',
                        '#FF748B',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "نمودار کل هزینه ها"
                }
            }
        });
    </script>
    </script>
@endsection
