<?php

namespace Modules\UserManagement\Http\Requests;

use App\Http\Requests\Concerns\PesanIndonesia;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use PesanIndonesia;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isStore = $this->isMethod('post');
        $user = $this->route('user');
        $ignoreId = is_object($user) ? $user->id : $user;

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.($ignoreId ?? 'NULL')],
            'password' => $isStore
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
        ];
    }

    public function messages(): array { return $this->pesanIndonesia(); }

    public function attributes(): array
    {
        return ['name' => 'Nama', 'email' => 'Email', 'password' => 'Password', 'role' => 'Role'];
    }
}
