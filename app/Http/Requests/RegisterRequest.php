<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PesanIndonesia;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use PesanIndonesia;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array { return $this->pesanIndonesia(); }

    public function attributes(): array
    {
        return ['name' => 'Nama', 'email' => 'Email', 'password' => 'Password'];
    }
}
