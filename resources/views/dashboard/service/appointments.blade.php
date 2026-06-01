@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>نوبت ها</title>
@endsection
@section('onvan')
   لیست نوبت ها
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">لیست نوبت ها</h5>
                <a data-bs-toggle="modal" data-bs-target="#addAppointmentModal" class="btn btn-danger float-start">خدمت
                    جدید</a>
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تاریخ</th>
                            <th>ساعت شروع</th>
                            <th>ساعت پایان</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $key => $appointment)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    {{ Jalalian::forge($appointment->day)->format('Y/m/d') }}
                                </td>
                                <td class="align-middle">{{ $appointment->start_time }}</td>
                                <td class="align-middle">{{ $appointment->end_time }}</td>
                                @if ($appointment->status == 0)
                                    <td class="align-middle text-warning">خالی</td>
                                @elseif($appointment->status == 1)
                                    <td class="align-middle text-primary">رزرو شده توسط
                                        <a href="#">{{ $appointment->customer_name }}</a>
                                    </td>
                                @elseif($appointment->status == 2)
                                    <td class="align-middle text-danger">کنسل شده</td>
                                @elseif($appointment->status == 3)
                                    <td class="align-middle text-success">پایان یافته</td>
                                @elseif($appointment->status == 4)
                                    <td class="align-middle text-danger">منقضی شده</td>
                                @endif
                                <td class="align-middle">
                                    {{-- <a href="#" class="text-success mx-1"><i
                                            class="fa-solid fa-pen-to-square"></i></a> --}}
                                    <a href="{{ route('appointments.delete', ['id' => $appointment->id]) }}"
                                        class="text-danger mx-1"><i class="fa-regular fa-trash-can"></i></a>
                                    {{-- <a href="#" class="text-primary mx-1"><i class="fa-solid fa-eye"></i></a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- مدال افزودن نوبت -->
    <div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-labelledby="addAppointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('appointments.store') }}" class="modal-content">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAppointmentModalLabel">افزودن نوبت جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="date" class="form-label">تاریخ نوبت:</label>
                        <input type="text" id="datePicker" class="form-control" required>
                        <input type="hidden" name="day" id="dateAlt" value="{{ old('day') }}">
                        @error('day')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">ساعت شروع:</label>
                        <input type="time" class="form-control" name="start_time" value="{{ old('start_time') }}"
                            required>
                        @error('start_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">ساعت پایان:</label>
                        <input type="time" class="form-control" name="end_time" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary">ثبت نوبت</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javaScript')
    <script>
        $(document).ready(function() {
            $("#datePicker").persianDatepicker({
                altField: '#dateAlt',
                altFormat: 'YYYY-MM-DD',
                format: 'D MMMM YYYY',
                observer: true,
                initialValue: false,
                autoClose: true,
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                }
            });
        });
    </script>
@endsection
