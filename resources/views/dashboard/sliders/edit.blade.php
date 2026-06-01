@extends('dashboard.layout.master')
@section('onvan')
   ویرایش اسلایدر
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-3 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <h4 class="mb-4">ویرایش اسلایدر</h4>
                <a href="/dashboard/sliders" class="btn btn-outline-danger">انصراف</a>
            </div> 

            <form action="{{ route('sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">عنوان</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $slider->title) }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">تصویر جدید (اختیاری)</label><br>
                    <img src="{{ asset($slider->image) }}" width="100" class="mb-2">
                    <input type="file" name="image" id="image" class="form-control">
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">لینک</label>
                    <input type="url" name="link" id="link" class="form-control"
                        value="{{ old('link', $slider->link) }}">
                    @error('link')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">وضعیت</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ $slider->status ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ !$slider->status ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
            </form>
        </div>
    </div>
@endsection
