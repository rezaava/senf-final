<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'mobile' => 'required|numeric|digits:11',
            // 'password' => 'required|string|min:8|max:20',
            // 'g-recaptcha-response' => 'required|captcha',
            'captcha' => 'required|captcha'
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.numeric' => 'شماره موبایل باید عدد باشد.',
            'mobile.digits' => 'شماره موبایل باید 11 رقم باشد.',

            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.string' => 'رمز عبور باید حروف و عدد باشد.',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد.',
            'password.max' => 'رمز عبور نمی‌تواند بیش از 20 کاراکتر باشد.',
            'captcha.required' => 'وارد کردن کد کپچا الزامی است',
            'captcha.captcha' => 'کد وارد شده استباه است لطفا دوباره تلاش کنید.',
        ];
    }
}
