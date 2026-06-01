@extends('dashboard.layout.master')
@section('onvan')
   لیست تیکت ها
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
            <h4>لیست تیکت‌ها</h4>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">ارسال تیکت جدید</a>

            @foreach ($tickets as $ticket)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $ticket->subject }}</h5>
                        <p>وضعیت: <strong>{{ $ticket->status }}</strong></p>
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-primary">مشاهده</a>
                    </div>
                </div>
            @endforeach

            {{ $tickets->links() }}
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
