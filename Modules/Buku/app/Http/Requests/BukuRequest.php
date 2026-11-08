<?php

namespace Modules\Buku\Http\Requests;

use App\Http\Requests\Concerns\PesanIndonesia;
use Illuminate\Foundation\Http\FormRequest;

class BukuRequest extends FormRequest
{
    use PesanIndonesia;

    public function authorize(): bool
    {
        return true; // hak akses dijaga oleh middleware role:admin pada route
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['required', 'string', 'max:150'],
            'tahun_terbit' => ['required', 'integer', 'digits:4', 'between:1900,'.date('Y')],
            'stok' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array { return $this->pesanIndonesia(); }

    public function attributes(): array
    {
        return [
            'judul' => 'Judul', 'penulis' => 'Penulis', 'penerbit' => 'Penerbit',
            'tahun_terbit' => 'Tahun terbit', 'stok' => 'Stok',
        ];
    }
}
