<?php

namespace App\Http\Requests\Concerns;

trait PesanIndonesia
{
    protected function pesanIndonesia(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'integer' => ':attribute harus berupa angka bulat.',
            'digits' => ':attribute harus terdiri dari :digits digit.',
            'date' => ':attribute bukan tanggal yang valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'file' => ':attribute harus berupa berkas.',
            'mimes' => ':attribute harus berformat: :values.',
            'after_or_equal' => ':attribute tidak boleh sebelum :date.',
            'between' => [
                'numeric' => ':attribute harus di antara :min dan :max.',
            ],
            'min' => [
                'string' => ':attribute minimal :min karakter.',
                'numeric' => ':attribute minimal :min.',
            ],
            'max' => [
                'string' => ':attribute maksimal :max karakter.',
                'file' => ':attribute maksimal :max KB (2 MB).',
            ],
        ];
    }
}
