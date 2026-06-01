@extends('dashboard.layout.master')
@section('head')
@endsection
@section('onvan')
مدیریت ساعت کاری
@endsection
@section('body')
    <div class="col">

        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="mb-4">مدیریت ساعات کاری</h4>

            <form action="{{ route('work-hour.store') }}" method="POST">
                @csrf

                <input type="hidden" name="user" value="{{ $user->id }}">
                @php
                    $days = [
                        0 => 'شنبه',
                        1 => 'یکشنبه',
                        2 => 'دوشنبه',
                        3 => 'سه‌شنبه',
                        4 => 'چهارشنبه',
                        5 => 'پنجشنبه',
                        6 => 'جمعه',
                    ];

                    $existing = $workhpurs->keyBy('day_of_week');
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>روز هفته</th>
                                <th>فعال</th>
                                <th>ساعت شروع</th>
                                <th>ساعت پایان</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($days as $index => $label)
                                @php
                                    $dayData = $existing[$index] ?? null;
                                @endphp

                                <tr>
                                    <td>{{ $label }}</td>

                                    {{-- فعال یا غیرفعال --}}
                                    <td>
                                        <input type="checkbox" name="working_hours[{{ $index }}][active]"
                                            value="1" {{ $dayData ? 'checked' : '' }}>
                                    </td>

                                    {{-- start_time --}}
                                    <td>
                                        <input type="time" class="form-control"
                                            name="working_hours[{{ $index }}][start_time]"
                                            value="{{ $dayData->start_time ?? '' }}">
                                    </td>

                                    {{-- end_time --}}
                                    <td>
                                        <input type="time" class="form-control"
                                            name="working_hours[{{ $index }}][end_time]"
                                            value="{{ $dayData->end_time ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        ذخیره ساعات کاری
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
