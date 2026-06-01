@extends('dashboard.layout.master')
@section('onvan')
بنر ها
@endsection
@section('main')
    <div class="col px-5">
        <div class="row mt-4 p-3 bg-white shadow rounded-4">
            <div class="d-flex justify-content-between mb-3">
                <h4>لیست بنرها</h4>
                <a href="{{ route('banners.create') }}" class="btn btn-success">افزودن بنر جدید</a>
            </div>
            <div class=" table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>تصویر</th>
                            <th>لینک</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td><img src="{{ asset($banner->image) }}" height="60"></td>
                                <td>{{ $banner->link }}</td>
                                <td>{{ $banner->status ? 'فعال' : 'غیرفعال' }}</td>
                                <td>
                                    <a href="{{ route('banners.edit', $banner) }}" class="btn btn-sm btn-primary">ویرایش</a>
                                    <form action="{{ route('banners.destroy', $banner) }}" method="POST"
                                        class="d-inline-block">
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

            {{ $banners->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
