@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('title')
    <title>لیست خدمات</title>
@endsection
@section('onvan')
   لیست خدمات
@endsection
@section('body')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">لیست خدمات</h5>
                <a href="{{route('service.create')}}" class="btn btn-danger float-start">خدمت جدید</a>
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عکس</th>
                            <th>نام</th>
                            <th>قیمت پایه</th>
                            <th>سقف قیمت</th>
                            {{-- <th>تخفیف</th>
                            <th>قیمت با تخفیف</th> --}}
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
                                <td class="align-middle">{{ $service->price }}</td>
                                <td class="align-middle">{{ $service->price_max ?? 'بدون سقف قیمت' }}</td>
                                {{-- <td class="align-middle">{{ $service->off }}</td>
                                <td class="align-middle">{{ $service->off_price }}</td> --}}
                                <td class="align-middle">{{ $service->description }}</td>
                                <td class="align-middle">
                                    <a href="{{route('service.edit',['id'=>$service->id])}}" class="text-success mx-1"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="{{route('service.delete',['id'=>$service->id])}}" class="text-danger mx-1"><i
                                            class="fa-regular fa-trash-can"></i></a>
                                    <a href="#" class="text-primary mx-1"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
