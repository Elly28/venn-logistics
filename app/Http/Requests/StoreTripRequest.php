<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
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
            'start_destination' => ['required', 'string', 'max:255'],
            'end_destination' => ['required', 'string', 'max:255'],
            'start_mileage' => ['required', 'integer', 'min:0'],
            'end_mileage' => ['required', 'integer', 'gte:start_mileage'], // Ensures end >= start
            'purpose' => ['required', 'string', 'max:1000'],
            'trip_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
