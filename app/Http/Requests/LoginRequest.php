<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PesanIndonesia;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use PesanIndonesia;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array { return $this->pesanIndonesia(); }

    public function attributes(): array
    {
        return ['email' => 'Email', 'password' => 'Password'];
    }
}
