<?php

namespace Modules\Buku\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok'];
}
