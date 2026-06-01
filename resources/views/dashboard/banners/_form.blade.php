@csrf

<div class="mb-3">
    <label for="image" class="form-label">تصویر بنر</label>
    <p><small class="text-danger">حداکثر حجم فایل: 4 مگابایت</small></p>
    <input type="file" name="image" class="form-control">
    @isset($banner)
        <img src="{{ asset($banner->image) }}" height="70" class="mt-2">
    @endisset
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="link" class="form-label">لینک</label>
    <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link ?? '') }}">
    @error('link')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">وضعیت</label>
    <select name="status" class="form-control">
        <option value="1" {{ old('status', $banner->status ?? 1) == 1 ? 'selected' : '' }}>فعال</option>
        <option value="0" {{ old('status', $banner->status ?? 1) == 0 ? 'selected' : '' }}>غیرفعال</option>
    </select>
    @error('status')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<button type="submit" class="btn btn-primary">ذخیره</button>
