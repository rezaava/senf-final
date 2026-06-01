@extends('dashboard.layout.master')
@section('onvan')
افزودن مدیا جدید
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 rounded-4 shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-3">افزودن مدیا جدید</h4>
            <a href="/dashboard/galleries" class="btn btn-outline-danger">انصراف</a>
        </div>

        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">فایل</label>
                <input type="file" name="media" class="form-control" required>
            </div>

            <button class="btn btn-primary">ذخیره</button>
        </form>
    </div>
</div>
@endsection
