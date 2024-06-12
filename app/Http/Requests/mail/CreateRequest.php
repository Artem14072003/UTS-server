<?php

namespace App\Http\Requests\mail;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'fullname' => ['required', 'string'],
            'tel' => ['required', "min:12", "max:16", "string"],
            'services' => ['required', "string"],
            'desc' => ['required', "min:10", "max:600", "string"]
        ];
    }

    public function messages()
    {
        return parent::messages() + [
                'required' => 'Перепроверьте поля!',
            ]; // TODO: Change the autogenerated stub
    }
}
