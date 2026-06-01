@extends('dashboard.layout.master')
@section('onvan')
   موضوع : {{ $ticket->subject }}
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
                <h4>موضوع: {{ $ticket->subject }}</h4>
                <a href="/dashboard/tickets" class="btn btn-outline-danger">انصراف</a>
            </div>
            <p>وضعیت: <strong>{{ $ticket->status }}</strong></p>

            <div class="card mb-4">
                <div class="card-body">
                    @foreach ($messages as $message)
                        <div class="mb-3 {{ $message->is_operator ? 'text-end' : '' }}">
                            <div
                                class="p-3 rounded border {{ $message->is_operator ? 'bg-light border-primary border' : '' }}">
                                <small><strong>{{ $message->user->name }}</strong> -
                                    {{ jdate($message->created_at)->format('Y/m/d H:i') }}</small><br>
                                <div class="mt-4">
                                    {{ $message->message }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- @if (Auth::user()->hasRole('admin')) --}}
            <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ارسال پاسخ</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">ارسال پاسخ</button>
            </form>
            {{-- @endif --}}
        </div>
    </div>
@endsection
@section('javaScript')
@endsection
