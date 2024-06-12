<?php

namespace App\Http\Requests\truck;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "image" => ['required', 'array'],
            "title" => ['required'],
            "desc" => ['required'],
            "price" => ['required'],
            "model" => ['required'],
            "year_release" => ['required'],
            "wheel_formula" => ['required'],
            "engine_power" => ['required'],
            "transmission" => ['required'],
            "fuel" => ['required'],
            "weight" => ['required'],
            "load_capacity" => ['required'],
            "engine_model" => ['required'],
            "wheels" => ['required'],
            "guarantee" => ['required'],
            "add" => ['nullable', 'array'],
        ];
    }
}
