@extends('dashboard.layout.master')

@section('head')
    <title>دسته بندی جدید</title>
    <style>
        .submit-btn {
            background-color: #003652;
            color: #ffffff;
        }
    </style>
@endsection
@section('onvan')
    افزودن دسته بندی ها
@endsection

@section('main')
    <!-- main  -->
    <div class="col">
        <!-- Form -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white pb-3">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <h5 class="float-end">افزودن دسته بندی</h5>
                <a href="/dashboard/categories" class="btn btn-outline-danger">انصراف</a>
            </div>
            <form action="{{ route('category.newPost') }}" class="px-4" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Product's name -->
                <div class="mb-3">
                    <label for="name" class="form-label">نام دسته بندی<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="name" placeholder="نام دسته بندی" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row mt-3">
                    <!-- description -->
                    <div class="col-md-4 mb-3">
                        <label for="description" class="form-label">توضیحات :</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="parent" class="form-label">والد</label>
                                <select class="form-select" name="parent" id="parent">
                                    <option selected disabled>انتخاب کنید</option>
                                    <option value="">هیچکدام</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('parent') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">قیمت (تومان)</label>
                            <input type="number" class="form-control" name="price" id="price"
                                placeholder="قیمت پایه دسته بندی را وارد کنید" />
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- image -->
                    <div class="col-md-4 mb-3">
                        <label for="image" class="form-label">عکس:</label>
                        <input type="file" class="form-control" id="image" name="image"
                            value="{{ old('image') }}">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit"
                        class="btn submit-btn w-25 border border-3 border-dark align-middle rounded-pill shadow">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
@endsection
