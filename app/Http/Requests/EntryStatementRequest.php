<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntryStatementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // عدّل حسب صلاحيات التطبيق
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            'car_type' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
            'car_number' => 'required|string|max:255',
            'car_brand' => 'string|max:255',
            'car_nationality' => 'required|string|max:255',
            'border_crossing_id' => 'required',
            'stay_duration' => 'min:0',
            'stay_fee' => 'numeric|min:0',
            'is_checked_out' => 'boolean',
            'has_commitment' => 'boolean',
            'exit_fee' => 'numeric|min:0',
            'book_number' => 'nullable|max:255',
            'book_type' => 'nullable|max:255',
            'type' => 'required|max:255',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'car_type.required' => 'يرجى إدخال نوع السيارة.',
            'driver_name.required' => 'يرجى إدخال اسم السائق.',
            'car_brand.required' => 'يرجى إدخال ماركة السيارة.',
            'car_nationality.required' => 'يرجى إدخال جنسية السيارة.',
            'car_number.required' => 'يرجى إدخال رقم السيارة.',
            'stay_duration.required' => 'يرجى إدخال مدة البقاء.',
            'stay_fee.required' => 'يرجى إدخال رسم البقاء.',
            'is_checked_out.required' => 'يرجى تحديد ما إذا تم تسجيل الخروج.',
            'exit_fee.required' => 'يرجى إدخال رسم الخروج.',
        ];
    }
}
