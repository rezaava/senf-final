@extends('dashboard.layout.master')
@section('onvan')
ویدیو های آموزشی
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-2 rounded-4 shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-4">{{ isset($educationalVideo) ? 'ویرایش' : 'افزودن' }} ویدیو آموزشی</h4>
            <a href="/dashboard/educational-videos" class="btn btn-outline-danger">انصراف</a>
        </div>

        <form action="{{ isset($educationalVideo) ? route('educational-videos.update', $educationalVideo->id) : route('educational-videos.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($educationalVideo)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">عنوان</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $educationalVideo->title ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="form-control">{{ old('description', $educationalVideo->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">تصویر (کاور)</label>
                <input type="file" name="thumbnail" class="form-control">
                @isset($educationalVideo)
                    <img src="{{ asset($educationalVideo->thumbnail) }}" class="mt-2" width="100">
                @endisset
            </div>

            <div class="mb-3">
                <label class="form-label">فایل ویدیو</label>
                <input type="file" name="video_path" class="form-control">
                @isset($educationalVideo)
                    <video width="200" controls class="mt-2">
                        <source src="{{ asset($educationalVideo->video_path) }}" type="video/mp4">
                    </video>
                @endisset
            </div>

            <div class="mb-3">
                <label class="form-label">نام استاد</label>
                <input type="text" name="teacher_name" class="form-control"
                       value="{{ old('teacher_name', $educationalVideo->teacher_name ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">مدت زمان (ساعت)</label>
                <input type="number" name="duration_hours" class="form-control"
                       value="{{ old('duration_hours', $educationalVideo->duration_hours ?? '') }}">
            </div>

            {{-- <div class="mb-3 form-check">
                <input type="checkbox" name="status" value="true" class="form-check-input"
                       {{ old('status', $educationalVideo->status ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">فعال</label>
            </div> --}}

            <button type="submit" class="btn btn-primary">ثبت</button>
        </form>
    </div>
</div>
@endsection
