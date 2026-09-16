@extends('dashboard.layout.master')
@section('onvan')
گالری
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 rounded-4 shadow bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>گالری</h4>
            <a href="{{ route('galleries.create') }}" class="btn btn-success">افزودن مدیا</a>
        </div>
        
        @foreach($galleries as $gallery)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $gallery->title }}</h5>
                        <p>{{ $gallery->description }}</p>
                        @if($gallery->type === 'image')
                            <img src="{{ asset($gallery->path) }}"  style="width:100%;object-fit:cover;">
                        @else
                            <video controls style="width:100%; max-width:300px;">
                                <source src="{{ asset($gallery->path) }}" type="video/mp4">
                            </video>
                        @endif
                        <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" class="mt-2">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
