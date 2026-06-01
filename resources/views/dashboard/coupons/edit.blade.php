@extends('dashboard.layout.master')
@section('onvan')
ویرایش کد تخفیف
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 bg-white shadow rounded-4">
        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="mb-4">ویرایش کد تخفیف</h4>
            <a href="/dashboard/coupons" class="btn btn-outline-danger">انصراف</a>
        </div>
        <form action="{{ route('coupons.update', $coupon) }}" method="POST">
            @method('PUT')
            @include('dashboard.coupons._form', ['coupon' => $coupon])
        </form>
    </div>
</div>
@endsection
