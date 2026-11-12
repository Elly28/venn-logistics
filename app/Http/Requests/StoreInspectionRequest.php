<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'inspection_date' => ['required', 'date'],
            'items_checked' => ['required', 'array'],
            'items_checked.*.item' => ['required', 'string'],
            'items_checked.*.passed' => ['required', 'boolean'],
            'status' => ['required', 'in:pass,fail,requires_attention'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
