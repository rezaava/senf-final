@extends('dashboard.layout.master')
@section('onvan')
ویرایش قرارداد
@endsection
@section('main')
    <div class="col px-5">
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <h4 class="mb-4">ویرایش قرارداد</h4>
                <a href="/dashboard/contract-templates" class="btn btn-outline-danger">انصراف</a>
            </div>


            <form action="{{ route('contract-templates.update', $contractTemplate->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">عنوان قرارداد</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $contractTemplate->title) }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="target_role" class="form-label">نوع طرف قرارداد</label>
                    <select name="target_role" id="target_role" class="form-control">
                        <option value="manager" {{ $contractTemplate->target_role == 'manager' ? 'selected' : '' }}>مدیر سالن
                        </option>
                        <option value="operator" {{ $contractTemplate->target_role == 'operator' ? 'selected' : '' }}>آرایشگر
                        </option>
                    </select>
                    @error('target_role')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3" id="type-wrapper" style="display: none;">
                    <label for="type" class="form-label">نوع قرارداد برای آرایشگر</label>
                    <select name="type" id="type" class="form-control">
                        <option value="fixed" {{ $contractTemplate->type == 'fixed' ? 'selected' : '' }}>حقوق ثابت</option>
                        <option value="percentage" {{ $contractTemplate->type == 'percentage' ? 'selected' : '' }}>درصدی</option>
                        <option value="chair_rent" {{ $contractTemplate->type == 'chair_rent' ? 'selected' : '' }}>اجاره صندلی
                        </option>
                    </select>
                    @error('type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="text" class="form-label">متن قرارداد</label>
                    <textarea name="text" rows="10" class="form-control">{{ old('text', $contractTemplate->text) }}</textarea>
                    @error('text')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3" id="percentage-wrapper" style="display: none;">
                    <label for="percentage" class="form-label">درصد پیش‌فرض</label>
                    <input type="number" name="percentage" class="form-control"
                        value="{{ old('percentage', $contractTemplate->percentage) }}" min="1" max="100">
                    @error('percentage')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3" id="services-wrapper" style="display: none;">
                    <h4>درصد هر خدمت</h4>
                    @foreach ($contractTemplate->services as $service)
                        @php
                            $value = old(
                                'service.' . $service->id,
                                $contractTemplate->services->find($service->id)?->pivot->percentage,
                            );
                        @endphp
                        <div class="mb-3">
                            <label for="service[{{ $service->id }}]" class="form-label">{{ $service->name }}</label>
                            <input type="number" name="service[{{ $service->id }}]" class="form-control"
                                value="{{ $value }}" min="1" max="100">
                            @error('service.' . $service->id)
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="mb-3" id="amount-wrapper" style="display: none;">
                    <label for="amount" class="form-label">مبلغ (تومان)</label>
                    <input type="number" name="amount" class="form-control"
                        value="{{ old('amount', $contractTemplate->amount) }}" min="1000">
                    @error('amount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const roleSelect = document.getElementById("target_role");
        const typeSelect = document.getElementById("type");
        const typeWrapper = document.getElementById("type-wrapper");
        const percentageWrapper = document.getElementById("percentage-wrapper");
        const servicesWrapper = document.getElementById("services-wrapper");
        const amountWrapper = document.getElementById("amount-wrapper");

        function toggleFields() {
            const role = roleSelect.value;
            const type = typeSelect.value;

            if (role === 'manager') {
                typeWrapper.style.display = "none";
                percentageWrapper.style.display = "block";
                amountWrapper.style.display = "none";
                servicesWrapper.style.display = "none";
            } else if (role === 'operator') {
                typeWrapper.style.display = "block";

                if (type === 'percentage') {
                    percentageWrapper.style.display = "block";
                    servicesWrapper.style.display = "block";
                    amountWrapper.style.display = "none";
                } else {
                    percentageWrapper.style.display = "none";
                    servicesWrapper.style.display = "none";
                    amountWrapper.style.display = "block";
                }
            } else {
                typeWrapper.style.display = "none";
                percentageWrapper.style.display = "none";
                amountWrapper.style.display = "none";
            }
        }

        roleSelect.addEventListener("change", toggleFields);
        typeSelect.addEventListener("change", toggleFields);
        toggleFields();
    </script>
@endsection
