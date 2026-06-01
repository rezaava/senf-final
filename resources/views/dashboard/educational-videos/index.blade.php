@extends('dashboard.layout.master')
@section('onvan')
مدیریت ویدیو های آموزشی
@endsection
@section('body')
    <div class="col px-5">
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between mb-3">
                <h4>مدیریت ویدیوهای آموزشی</h4>
                <a href="{{ route('educational-videos.create') }}" class="btn btn-success">افزودن ویدیو جدید</a>
            </div>

            @foreach ($videos as $video)
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="{{ asset($video->thumbnail) }}" class="img-fluid rounded-start"
                                alt="...">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title">{{ $video->title }} - {{ $video->teacher_name }}</h5>
                                <p class="card-text">{{ $video->description }}</p>
                                <p class="card-text"><small class="text-muted">ساعت: {{ $video->duration_hours }} | خرید:
                                        {{ $video->purchased_count }}</small></p>
                                <a href="{{ route('educational-videos.edit', $video->id) }}"
                                    class="btn btn-sm btn-primary">ویرایش</a>
                                <form action="{{ route('educational-videos.destroy', $video->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $videos->links() }}
        </div>
    </div>
@endsection
