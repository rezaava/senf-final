@extends('dashboard.layout.master')
@section('onvan')
مدیریت کدهای تخفیف
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-3 bg-white shadow rounded-4">
            <div class="d-flex justify-content-between mb-3">
                <h4>مدیریت کدهای تخفیف</h4>
                <a href="{{ route('coupons.create') }}" class="btn btn-success">ایجاد کد جدید</a>
            </div>

            <div class=" table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>کد</th>
                            <th>نوع</th>
                            <th>مقدار</th>
                            <th>محدودیت استفاده</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->type == 'percentage' ? 'درصدی' : 'مبلغ ثابت' }}</td>
                                <td>{{ $coupon->value }}</td>
                                <td>{{ $coupon->usage_limit }}</td>
                                <td>
                                    <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-primary">ویرایش</a>
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('حذف شود؟')"
                                            class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $coupons->links() }}
        </div>
    </div>
@endsection
