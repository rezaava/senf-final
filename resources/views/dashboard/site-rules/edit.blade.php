@extends('dashboard.layout.master')
@section('onvan')
   ویرایش قوانین سایت
@endsection
@section('body')
<div class="col px-5">
    <div class="row mt-4 p-3 rounded-4 shadow bg-white">
        <h4 class="mb-4">ویرایش قوانین و مقررات سایت</h4>

        <form action="{{ route('site-rules.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">متن قوانین</label>
                <textarea name="content" rows="10" class="form-control" required>{{ old('content', $rule->content) }}</textarea>
                @error('content')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">ذخیره</button>
        </form>
    </div>
</div>
@endsection
