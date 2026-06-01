<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganRequest extends FormRequest
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
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|digits:11|unique:organs,mobile',
            'postalCode' => 'nullable|string|max:10',
            'RegistrationDate' => 'nullable',
            'contract' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام الزامی است.',
            'name.max' => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'description.max' => 'توضیحات نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'image.image' => 'فرمت عکس نامعتبر است.',

            'address.required' => 'آدرس الزامی است.',
            'address.max' => 'آدرس نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'phone.required' => 'تلفن الزامی است.',
            'phone.max' => 'تلفن نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'email.email' => 'فرمت ایمیل معتبر نیست.',
            'email.max' => 'ایمیل نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'mobail.required' => 'شماره موبایل الزامی است.',
            'mobail.digits' => 'شماره موبایل باید 11 رقم باشد.',
            'mobail.unique' => 'شماره موبایل تکراری است.',

            'postalCode.max' => 'کد پستی نباید بیشتر از ۱۰ کاراکتر باشد.',

            'RegistrationDate.date' => 'تاریخ ثبت باید یک تاریخ معتبر باشد.',
            'contract.required' => 'انتخاب قرارداد الزامی است.',
        ];
    }
}
