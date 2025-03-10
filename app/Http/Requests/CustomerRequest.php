<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'image' =>['nullable', 'image', 'max:3000'],
            'first_name' =>['required', 'string', 'max:30', 'min:3'],
            'last_name' =>['required', 'string', 'max:30', 'min:3'],
            'email'=>['required', 'email',],
            'phone'=>['required', 'string', 'max:15'],
            'bank_account_number' =>['required', 'max:20'],
            'about'=>['nullable', 'string', 'max:500', 'min:5'],
        ];
    }
}