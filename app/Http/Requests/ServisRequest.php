<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'organ_id' => 'required|exists:organs,id',
            'price' => 'required|integer|min:0',
            'off' => 'integer|min:0|max:100',
            'description' => 'required|string',
            'comment_counts' => 'sometimes|string',
            'score' => 'sometimes|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام سرویس الزامی است.',
            'organ_id.required' => 'انتخاب سازمان الزامی است.',
            'organ_id.exists' => 'سازمان انتخاب شده معتبر نیست.',
            'price.required' => 'قیمت سرویس الزامی است.',
            'price.integer' => 'قیمت باید عدد صحیح باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',
            'off.integer' => 'تخفیف باید عدد صحیح باشد.',
            'off.min' => 'تخفیف نمی‌تواند منفی باشد.',
            'off.max' => 'تخفیف نمی‌تواند بیشتر از 100 باشد.',
            'description.required' => 'توضیحات سرویس الزامی است.',
        ];
    }
}
