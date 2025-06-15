<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email'=>'required|email',
            'password'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Mohon mengisi kolom email',
            'email.email' => 'Mohon memasukkan email yang valid',
            'password.required' => 'Mohon mengisi kolom password',
        ];
    }
}
