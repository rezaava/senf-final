@extends('dashboard.layout.master')
@section('onvan')
   مدیریت اسلایدرها
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-3 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>مدیریت اسلایدرها</h4>
                <a href="{{ route('sliders.create') }}" class="btn btn-success">افزودن اسلایدر جدید</a>
            </div>
            <div class=" table-responsive">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>تصویر</th>
                            <th>لینک</th>
                            <th>محل نمایش</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $slider)
                            <tr>
                                <td>{{ $slider->title }}</td>
                                <td><img src="{{ asset($slider->image) }}" alt="" width="100"></td>
                                <td>{{ $slider->link ?? '-' }}</td>
                                <td>{{ $slider->type == 1 ? 'صفحه اصلی' : 'تبلیغات' }}</td>
                                <td>{{ $slider->status ? 'فعال' : 'غیرفعال' }}</td>
                                <td>
                                    <a href="{{ route('sliders.edit', $slider) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                    <form action="{{ route('sliders.destroy', $slider) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('حذف شود؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sliders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
