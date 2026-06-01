@extends('dashboard.layout.master')
@section('onvan')
افزودن بنر جدید
@endsection
@section('main')
<div class="col px-5">
    <div class="row mt-4 p-3 bg-white shadow rounded-4">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-4">افزودن بنر جدید</h4>
            <a href="/dashboard/banners" class="btn btn-outline-danger">انصراف</a>
        </div>

        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
            @include('dashboard.banners._form')
        </form>
    </div>
</div>
@endsection
