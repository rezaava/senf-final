<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'password' => 'required|string|min:8|max:20',
            'email' => 'nullable|email|max:255|unique:users,email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'وارد کردن نام الزامی است.',
            'name.string' => 'نام باید حروف باشد.',
            'name.max' => 'نام نمی‌تواند بیش از 255 کاراکتر باشد.',
            
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.numeric' => 'شماره موبایل باید عدد باشد.',
            'mobile.digits' => 'شماره موبایل باید 11 رقم باشد.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.string' => 'رمز عبور باید حروف و عدد باشد.',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد.',
            'password.max' => 'رمز عبور نمی‌تواند بیش از 20 کاراکتر باشد.',
            
            'email.email' => 'فرمت ایمیل وارد شده معتبر نیست.',
            'email.max' => 'ایمیل نمی‌تواند بیش از 255 کاراکتر باشد.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.'
        ];
    }
}