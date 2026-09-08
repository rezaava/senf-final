@extends('dashboard.layout.master')
@section('onvan')
   ایجاد اسلایدر جدید
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 rounded-4 shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-4">ایجاد اسلایدر جدید</h4>
            <a href="/dashboard/sliders" class="btn btn-outline-danger">انصراف</a>
        </div>

        <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">عنوان</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">تصویر</label>
                <input type="file" name="image" id="image" class="form-control">
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">لینک (اختیاری)</label>
                <input type="url" name="link" id="link" class="form-control" value="{{ old('link') }}">
                @error('link') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">وضعیت</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>فعال</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>غیرفعال</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">محل انتخاب نمایش</label>
                <select name="type" id="type" class="form-control">
                    <option value="">انتخاب کنید</option>
                    <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>صفحه اصلی</option>
                    <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>تبلیغات</option>
                </select>
                @error('type') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">ثبت اسلایدر</button>
        </form>
    </div>
</div>
@endsection
