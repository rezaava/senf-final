@extends('dashboard.layout.master')
@section('onvan')
گزارش دقیق سالن: {{ $organ->name }}
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-3 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>گزارش دقیق سالن: {{ $organ->name }}</h4>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">بازگشت</a>
            </div>

            {{-- فیلترها --}}
            <form action="{{ route('reports.show', $organ) }}" method="GET" class="row mb-4">
                <div class="col-md-3">
                    <label>از تاریخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>تا تاریخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>انتخاب اپراتور</label>
                    <select name="operator_id" class="form-control">
                        <option value="">همه اپراتورها</option>
                        @foreach ($operators as $operator)
                            <option value="{{ $operator->id }}"
                                {{ request('operator_id') == $operator->id ? 'selected' : '' }}>
                                {{ $operator->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>انتخاب خدمت</label>
                    <select name="service_id" class="form-control">
                        <option value="">همه خدمات</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3 text-end">
                    <button class="btn btn-primary">فیلتر</button>
                    <a href="{{ route('reports.show', $organ) }}" class="btn btn-secondary">حذف فیلتر</a>
                </div>
            </form>

            {{-- جدول گزارش --}}
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>ساعت</th>
                        <th>کاربر</th>
                        <th>خدمت</th>
                        <th>اپراتور</th>
                        <th>قیمت</th>
                        <th>سهم اپ</th>
                        <th>سهم سالن</th>
                        <th>سهم اپراتور</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->day }}</td>
                            <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                            <td>{{ $appointment->user->name ?? 'مهمان' }}</td>
                            <td>{{ $appointment->service->name ?? '-' }}</td>
                            <td>{{ $appointment->operator->name ?? '-' }}</td>
                            <td>{{ number_format($appointment->price) }} تومان</td>
                            <td>{{ number_format($appointment->app_share) }} تومان</td>
                            <td>{{ number_format($appointment->organ_share) }} تومان</td>
                            <td>{{ number_format($appointment->operator_share) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $appointments->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
