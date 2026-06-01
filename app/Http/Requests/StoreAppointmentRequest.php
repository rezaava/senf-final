<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Appointment;
use Morilog\Jalali\Jalalian;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'day'         => ['required'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'service_id'  => ['required', 'exists:services,id'],
        ];
    }

    public function messages()
    {
        return [
            'day.required'         => 'تاریخ الزامی است.',
            'start_time.required'  => 'ساعت شروع الزامی است.',
            'start_time.date_format' => 'فرمت ساعت شروع معتبر نیست.',
            'end_time.required'    => 'ساعت پایان الزامی است.',
            'end_time.after'       => 'ساعت پایان باید بعد از ساعت شروع باشد.',
            'service_id.required'  => 'انتخاب خدمت الزامی است.',
            'service_id.exists'    => 'خدمت انتخاب شده معتبر نیست.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $persianNumbers  = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $englishNumbers  = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

            $dayInput = str_replace($persianNumbers, $englishNumbers, $this->input('day'));
            try {
                $date = Jalalian::fromFormat('Y-m-d', $dayInput)->toCarbon()->format('Y-m-d');
            } catch (\Exception $e) {
                $validator->errors()->add('day', 'تاریخ وارد شده معتبر نیست.');
                return;
            }

            $start     = $this->input('start_time');
            $end       = $this->input('end_time');
            $serviceId = $this->input('service_id');
            $userId    = auth()->id();

            // بررسی تداخل نوبت
            $hasOverlap = Appointment::where('day', $date)
                ->where('service_id', $serviceId)
                ->where('user_id', $userId)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                })
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add('start_time', 'این بازه زمانی با نوبت دیگری تداخل دارد.');
            }
        });
    }
}
