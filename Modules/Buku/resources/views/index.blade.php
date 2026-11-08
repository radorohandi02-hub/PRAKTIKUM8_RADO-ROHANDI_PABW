@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Daftar Buku</h3>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('buku.create') }}" class="btn btn-primary">+ Tambah Buku</a>
    @endif
</div>

<form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ $q }}" class="form-control"
               placeholder="Cari judul, penulis, atau penerbit...">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Cari</button>
        @if($q !== '')<a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>@endif
    </div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle bg-white">
    <thead class="table-primary">
        <tr>
            <th style="width:50px">#</th><th>Judul</th><th>Penulis</th><th>Penerbit</th>
            <th>Tahun</th><th>Stok</th>
            @if(auth()->user()->isAdmin())<th style="width:160px">Aksi</th>@endif
        </tr>
    </thead>
    <tbody>
    @forelse($buku as $i => $b)
        <tr>
            <td>{{ $buku->firstItem() + $i }}</td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->penerbit }}</td>
            <td>{{ $b->tahun_terbit }}</td>
            <td>{{ $b->stok }}</td>
            @if(auth()->user()->isAdmin())
            <td>
                <a href="{{ route('buku.edit', $b) }}" class="btn btn-sm btn-warning">Ubah</a>
                <form action="{{ route('buku.destroy', $b) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
            @endif
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">Data buku tidak ditemukan.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{ $buku->links('pagination::bootstrap-5') }}
@endsection
