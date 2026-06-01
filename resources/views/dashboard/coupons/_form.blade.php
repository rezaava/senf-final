@csrf

<div class="mb-3">
    <label for="title" class="form-label">عنوان</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title ?? '') }}">
    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">توضیحات</label>
    <textarea name="description" class="form-control">{{ old('description', $coupon->description ?? '') }}</textarea>
    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="code" class="form-label">کد تخفیف</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}">
    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="type" class="form-label">نوع تخفیف</label>
    <select name="type" class="form-control">
        <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
        <option value="percentage" {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>درصدی</option>
    </select>
    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="value" class="form-label">مقدار تخفیف</label>
    <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value ?? '') }}" min="1">
    @error('value') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="usage_limit" class="form-label">تعداد قابل استفاده</label>
    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit ?? 1) }}" min="1">
    @error('usage_limit') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<button type="submit" class="btn btn-primary">ذخیره</button>
