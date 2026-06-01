@extends('dashboard.layout.master')
@section('onvan')
   خدمت جدید
@endsection
@section('title')
    <title>خدمت جدید</title>
    <style>
        .submit-btn {
            background-color: #003652;
            color: #ffffff;
        }
    </style>
@endsection

@section('body')
    <div class="col px-3">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="row g-0 mt-4 p-3 rounded-4 shadow bg-white pb-3">
            <div class="clearfix mt-2">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                    <h5 class="float-end">خدمت جدید</h5>
                    <a href="/dashboard/services" class="btn btn-outline-danger">انصراف</a>
                </div>
            </div>
            <form action="{{ route('service.store') }}" class="px-4 mt-4" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- نام خدمت -->
                <div class="mb-3">
                    <label for="name" class="form-label">نام خدمت <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="name" placeholder="نام خدمت" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <!-- قیمت -->
                        <div class="mb-3">
                            <label for="price" class="form-label">قیمت <span class="text-danger">*</span> :</label>
                            <input type="number" class="form-control" id="price" placeholder="قیمت خدمت" name="price"
                                value="{{ old('price') }}">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- سقف قیمت -->
                        <div class="mb-3">
                            <label for="price_max" class="form-label">سقف قیمت :</label>
                            <input type="number" class="form-control" id="price_max" placeholder="سقف قیمت خدمت" name="price_max"
                                value="{{ old('price_max') }}">
                            @error('price_max')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <!-- نوع تخفیف -->
                        <div class="mb-3">
                            <label class="form-label">نوع تخفیف :</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="off_type" id="percent"
                                        value="1"
                                        @if (old('off_type')) {{ old('off_type') == '1' ? 'checked' : '' }}
                                        @else checked @endif>
                                    <label class="form-check-label" for="percent">درصدی</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="off_type" id="amount"
                                        value="2" {{ old('off_type') == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amount">مبلغی</label>
                                </div>
                            </div>
                            @error('off_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- مقدار تخفیف -->
                        <div class="mb-3">
                            <label for="off" class="form-label">مقدار تخفیف :</label>
                            <input type="number" class="form-control" id="off" placeholder="مقدار تخفیف"
                                name="off" value="{{ old('off', 0) }}">
                            @error('off')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div> --}}
                </div>
                <!-- توضیحات -->
                <div class="mb-3">
                    <label for="description" class="form-label">توضیحات :</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <!-- عکس -->
                <div class="mb-3">
                    <label for="image" class="form-label">عکس خدمت :</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit"
                        class="btn submit-btn w-25 border border-3 border-dark align-middle rounded-pill shadow">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
@endsection
