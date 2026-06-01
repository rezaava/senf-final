@extends('dashboard.layout.master')
@section('onvan')
  تراکنش ها
@endsection
@section('title')
    <title>پروفایل</title>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    <!-- main  -->
    <div class="col pb-5">
        {{-- تراکنش ها --}}
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="pt-3 px-4">تراکنش ها</h4>
            <!-- فرم فیلتر -->
            <form method="GET" class="row g-3 mb-4">

                @if (auth()->user()->hasRole('admin'))
                    <div class="col-md-3">
                        <label for="organ_id" class="form-label">سالن</label>
                        <select class="form-select" name="organ_id" id="organ_id">
                            <option value="">همه</option>
                            @foreach ($organs as $organ)
                                <option value="{{ $organ->id }}"
                                    {{ request('organ_id') == $organ->id ? 'selected' : '' }}>
                                    {{ $organ->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                    <div class="col-md-3">
                        <label for="operator_id" class="form-label">اپراتور</label>
                        <select class="form-select" name="operator_id" id="operator_id">
                            <option value="">همه</option>
                            @foreach ($operators as $operator)
                                <option value="{{ $operator->id }}"
                                    {{ request('operator_id') == $operator->id ? 'selected' : '' }}>
                                    {{ $operator->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-3">
                    <label for="from_date" class="form-label">از تاریخ</label>
                    <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="to_date" class="form-label">تا تاریخ</label>
                    <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">فیلتر</button>
                </div>
            </form>
            <!-- charts -->
            <div class="col-md-12 my-auto p-3 mt-3">
                <div class="table-responsive-sm">
                    <table class="table table-bordered mt-2 text-center">
                        <thead>
                            <tr class="bg-secondary">
                                @if (auth()->user()->hasRole('admin'))
                                    <th></th>
                                    <th></th>
                                @elseif (auth()->user()->hasRole('manager'))
                                    <th></th>
                                    {{-- @else
                                    <th>سالن</th> --}}
                                @endif
                                <th></th>
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
                                <th>کاربر</th>
                                @if (auth()->user()->hasRole('admin'))
                                    <th>سالن</th>
                                    <th>اپراتور</th>
                                @elseif (auth()->user()->hasRole('manager'))
                                    <th>اپراتور</th>
                                    {{-- @else
                                    <th>سالن</th> --}}
                                @endif
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
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="align-middle">{{ $transaction->id }}</td>
                                    <td class="align-middle">{{ $transaction->user->name ?? '' }}</td>
                                    @if (auth()->user()->hasRole('admin'))
                                        <td class="align-middle">{{ $transaction->organ->name ?? '' }}</td>
                                        <td class="align-middle">{{ $transaction->operator->name ?? '' }}</td>
                                    @elseif (auth()->user()->hasRole('manager'))
                                        <td class="align-middle">{{ $transaction->operator->name ?? '' }}</td>
                                        {{-- @else
                                    <th>سالن</th> --}}
                                    @endif
                                    <td class="align-middle">{{ jdate($transaction->crated_at)->format('Y/m/d') }}</td>
                                    <td class="align-middle">{{ jdate($transaction->crated_at)->format('H:i:s') }}</td>
                                    <td class="align-middle">{{ $transaction->description }}</td>
                                    @if ($transaction->price > 0)
                                        <td class="align-middle">{{ number_format($transaction->price) }}</td>
                                        <td class="align-middle">0</td>
                                    @else
                                        <td class="align-middle">0</td>
                                        <td class="align-middle">{{ number_format($transaction->price) }}</td>
                                    @endif
                                    @if ($transaction->user and $transaction->user->wallet > 0)
                                        <td class="align-middle">{{ number_format($transaction->user->wallet ?? 0) }}</td>
                                        <td class="align-middle"></td>
                                    @else
                                        <td class="align-middle"></td>
                                        <td class="align-middle">{{ number_format($transaction->user->wallet ?? 0) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
