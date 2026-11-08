@extends('layouts.app')
@php $isEdit = $buku->exists; @endphp
@section('title', $isEdit ? 'Ubah Buku' : 'Tambah Buku')
@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">{{ $isEdit ? 'Ubah Buku' : 'Tambah Buku' }}</h4>
        <form action="{{ $isEdit ? route('buku.update', $buku) : route('buku.store') }}" method="POST" novalidate>
            @csrf
            @if($isEdit) @method('PUT') @endif

            @foreach(['judul' => 'Judul', 'penulis' => 'Penulis', 'penerbit' => 'Penerbit'] as $f => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}</label>
                    <input type="text" name="{{ $f }}" value="{{ old($f, $buku->$f) }}"
                           class="form-control @error($f) is-invalid @enderror">
                    @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach

            @foreach(['tahun_terbit' => 'Tahun Terbit', 'stok' => 'Stok'] as $f => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}</label>
                    <input type="number" name="{{ $f }}" value="{{ old($f, $buku->$f) }}"
                           class="form-control @error($f) is-invalid @enderror">
                    @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
