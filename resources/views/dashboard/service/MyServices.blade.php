@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>خدمات من</title>
@endsection
@section('onvan')
   خدمات من
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                    <h5 class="float-end">خدمات من</h5>
                    <a data-bs-toggle="modal" data-bs-target="#companyModal" class="btn btn-danger float-start">انتخاب خدمت جدید </a>
                </div>
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عکس</th>
                            <th>نام</th>
                            <th>سالن</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>قیمت با تخفیف</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $key => $service)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" width="80px">
                                </td>
                                <td class="align-middle">{{ $service->name }}</td>
                                <td class="align-middle">{{ $service->organ->name }}</td>
                                <td class="align-middle">{{ $service->price }}</td>
                                <td class="align-middle">{{ $service->off }}</td>
                                <td class="align-middle">{{ $service->off_price }}</td>
                                <td class="align-middle">{{ $service->description }}</td>
                                <td class="align-middle">
                                    {{-- <a href="{{route('service.edit',['id'=>$service->id])}}" class="text-success mx-1"><i
                                            class="fa-solid fa-pen-to-square"></i></a> --}}
                                    <a href="#" class="btn btn-danger mx-1 mt-2"><i
                                            class="fa-regular fa-trash-can mx-2"></i>حذف</a>
                                    <a href="{{route('appointments.index',['service'=>$service])}}" class="btn btn-primary mx-1 mt-2">
                                        <i class="fa-solid fa-clipboard-list mx-2"></i>
                                        نوبت ها
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">

                    <!-- عنوان -->
                    <h4 class="modal-title fw-bold">
                        شرکت یک
                    </h4>

                    <!-- ضربدر -->
                    <button type="button"
                            class="btn-close me-end"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <!-- Body -->
                <div class="modal-body">

                    <form action="/dashboard/Myservices/add/service" method="POST">
                        @csrf
                        <!-- خدمات -->
                        <div class="mb-3">
                            <label class="form-label">خدمات سالن</label>
                            {{-- <input type="hidden" name="organ_id" value="{{ $organ->id }}"> --}}
                            <select class="form-select" name="service">
                                <option selected disabled>انتخاب خدمات</option>
                                @foreach ($organ_services as $organ_service )
                                    <option value="{{ $organ_service->id }}" >{{$organ_service->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- دکمه ذخیره -->
                        <button type="submit" class="btn btn-success w-100">
                            ذخیره
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
@section('javaScript')

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'خطا',
        text: '{{ session('error') }}',
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'موفق',
        text: '{{ session('success') }}',
    });
</script>
@endif

@endsection
