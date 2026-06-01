<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.unique' => 'این نام دسته‌بندی قبلا ثبت شده است.',
            'description.required' => 'توضیحات دسته‌بندی الزامی است.',
            'image.image' => 'فایل باید یک تصویر معتبر باشد.',
            'image.mimes' => 'فرمت تصویر باید یکی از موارد jpeg, png, jpg, gif باشد.',
            'image.max' => 'حجم تصویر نباید بیشتر از 2 مگابایت باشد.',
            'price.required' => 'قیمت دسته بندی الزامی میباشد.',
            'price.numeric' => 'قیمت دسته بندی عدد میباشد.'
        ];
    }
}
