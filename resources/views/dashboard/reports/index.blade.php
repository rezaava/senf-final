@extends('dashboard.layout.master')
@section('onvan')
گزارش درآمد سالن ها
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 rounded-4 shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>گزارش درآمد سالن‌ها</h4>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>نام سالن</th>
                    <th>کل درآمد سالن</th>
                    <th>سود اپلیکیشن</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organs as $organ)
                    <tr>
                        <td>{{ $organ->name }}</td>
                        <td>{{ number_format($organ->total_income) }} تومان</td>
                        <td>{{ number_format($organ->app_profit) }} تومان</td>
                        <td>
                            <a href="{{ route('reports.show', $organ) }}" class="btn btn-info btn-sm">مشاهده جزئیات</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
