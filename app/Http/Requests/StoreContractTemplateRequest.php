<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'target_role' => ['required', 'in:manager,operator'],
            'type' => ['nullable', 'in:fixed,percentage,chair_rent'],
            'text' => ['required', 'string', 'min:10'],
            'percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'amount' => ['nullable', 'integer', 'min:1000'],
            'service' => ['nullable', 'array'],
            'service.*' => ['nullable', 'integer', 'min:1', 'max:100'], // درصد برای هر خدمت
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $role = $this->input('target_role');
            $type = $this->input('type');
            $services = $this->input('service');

            if ($role === 'manager') {
                if (!$this->filled('percentage')) {
                    $validator->errors()->add('percentage', 'در قرارداد با مدیر سالن، وارد کردن درصد کلی الزامی است.');
                }
            }

            if ($role === 'operator') {
                if ($type === 'percentage') {
                    if (empty($services) || !is_array($services)) {
                        $validator->errors()->add('service', 'در قرارداد درصدی، وارد کردن درصد برای هر خدمت الزامی است.');
                    } else {
                        foreach ($services as $serviceId => $percent) {
                            if (!is_numeric($percent) || $percent < 1 || $percent > 100) {
                                $validator->errors()->add("service.$serviceId", "درصد هر خدمت باید عددی بین ۱ تا ۱۰۰ باشد.");
                            }
                        }
                    }
                }

                if (in_array($type, ['fixed', 'chair_rent']) && !$this->filled('amount')) {
                    $validator->errors()->add('amount', 'در قراردادهای حقوقی یا اجاره صندلی، وارد کردن مبلغ الزامی است.');
                }
            }
        });
    }
}
