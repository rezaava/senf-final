@extends('dashboard.layout.master')
@section('onvan')
شارژ کیف پول
@endsection
@section('title')
    <title>سفارشات</title>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endsection

@section('body')
    <div class="col pb-5">
        <div class="row g-0 mt-4 p-2 rounded-4 shadow bg-white">
            <h4 class="mb-4">شارژ کیف پول</h4>
            <div class="card p-4 mb-4">
                <p><strong>موجودی فعلی:</strong> {{ number_format($user->wallet ?? 0) }} تومان</p>

                <form action="{{ route('wallet.charge') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">مبلغ شارژ (تومان):</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="1000">
                    </div>
                    <button type="submit" class="btn btn-primary">شارژ کیف پول</button>
                </form>
            </div>
        </div>
    </div>
@endsection
