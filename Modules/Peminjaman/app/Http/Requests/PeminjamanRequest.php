<?php

namespace Modules\Peminjaman\Http\Requests;

use App\Http\Requests\Concerns\PesanIndonesia;
use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    use PesanIndonesia;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_peminjam' => ['required', 'string', 'max:100'],
            'judul_buku' => ['required', 'string', 'max:200'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'status' => ['required', 'in:Dipinjam,Dikembalikan'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array { return $this->pesanIndonesia(); }

    public function attributes(): array
    {
        return [
            'nama_peminjam' => 'Nama peminjam', 'judul_buku' => 'Judul buku',
            'tanggal_pinjam' => 'Tanggal pinjam', 'tanggal_kembali' => 'Tanggal kembali',
            'status' => 'Status', 'bukti' => 'Bukti peminjaman',
        ];
    }
}
