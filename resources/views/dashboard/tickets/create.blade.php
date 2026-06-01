@extends('dashboard.layout.master')
@section('onvan')
   ارسال تیکت جددی
@endsection
@section('title')
    <title>پروفایل</title>
    {{-- <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet"> --}}
@endsection

@section('body')
    <!-- main  -->
    <div class="col pb-5">
        {{-- تراکنش ها --}}
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <h4>ارسال تیکت جدید</h4>
                <a href="/dashboard/tickets" class="btn btn-outline-danger">انصراف</a>
            </div>

            <form method="POST" action="{{ route('tickets.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">موضوع</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">پیام</label>
                    <textarea name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">ارسال</button>
            </form>
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
