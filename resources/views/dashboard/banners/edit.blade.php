@extends('dashboard.layout.master')
@section('onvan')
ویرایش بنر
@endsection
@section('main')
<div class="col px-5">
    <div class="row mt-4 p-3 bg-white shadow rounded-4">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-4">ویرایش بنر</h4>
            <a href="/dashboard/banners" class="btn btn-outline-danger">انصراف</a>
        </div>

        <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('dashboard.banners._form', ['banner' => $banner])
        </form>
    </div>
</div>
@endsection
