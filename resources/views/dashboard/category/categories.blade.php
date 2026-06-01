@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('onvan')
    دسته بندی ها
@endsection

@section('main')
    <!-- main  -->
    <div class="col p-3 p-lg-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">دسته بندی ها</h5>
                <a href="{{route('category.new')}}" class="btn btn-danger float-start">افزودن دسته بندی جدید</a>
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>والد</th> --}}
                            <th>نام</th>
                            <th>قیمت پایه</th>
                            <th> توضیحات</th>
                            <th>عکس</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                {{-- <td class="align-middle">{{ $category->parent?->name ?? '--' }}</td> --}}
                                <td class="align-middle">{{ $category->name }}</td>
                                <td class="align-middle">{{ number_format($category->price) }} تومان</td>
                                <td class="align-middle">{{ $category->description }}</td>
                                <td class="align-middle">
                                    <img src="{{ asset($category->image) }}" alt="image" width="80px">
                                </td>
                                <td class="align-middle">
                                    <a href="/dashboard/categories/parent"
                                        class="text-success mx-1"><i class="fa-solid fa-right-to-bracket"></i></a>
                                    <a href="{{ route('category.edit', ['id' => $category->id]) }}"
                                        class="text-success mx-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="{{ route('category.delete', ['id' => $category->id]) }}"
                                        class="text-danger mx-1"><i class="fa-regular fa-trash-can"></i></a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
