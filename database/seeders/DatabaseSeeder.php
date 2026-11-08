<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Buku\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sipustaka.test'],
            ['name' => 'Petugas Admin', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'user@sipustaka.test'],
            ['name' => 'Anggota Perpustakaan', 'password' => 'password', 'role' => 'user']
        );

        if (Buku::count() === 0) {
            $data = [
                ['Pemrograman Web dengan Laravel', 'Budi Santoso', 'Informatika', 2022, 10],
                ['Basis Data Relasional', 'Siti Rahma', 'Andi Offset', 2020, 7],
                ['Algoritma dan Struktur Data', 'Rinaldi Munir', 'Informatika', 2019, 5],
                ['Jaringan Komputer', 'Andrew Tanenbaum', 'Pearson', 2018, 4],
                ['Rekayasa Perangkat Lunak', 'Roger Pressman', 'McGraw-Hill', 2021, 6],
                ['Kecerdasan Buatan', 'Stuart Russell', 'Pearson', 2020, 3],
            ];
            foreach ($data as [$judul, $penulis, $penerbit, $tahun, $stok]) {
                Buku::create(compact('judul', 'penulis', 'penerbit') + ['tahun_terbit' => $tahun, 'stok' => $stok]);
            }
        }
    }
}
