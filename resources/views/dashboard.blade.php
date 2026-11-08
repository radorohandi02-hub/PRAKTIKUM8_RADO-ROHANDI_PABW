@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Dashboard SiPustaka</h4>
        <p class="mb-1">Halo, <strong>{{ auth()->user()->name }}</strong>. Anda login sebagai
            <span class="badge bg-primary">{{ auth()->user()->role }}</span>.</p>
        <hr>
        <a href="{{ route('buku.index') }}" class="btn btn-outline-primary me-2">Daftar Buku</a>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary me-2">Peminjaman</a>
        {{-- Tautan ini dipakai untuk membuktikan error 403 saat login sebagai user --}}
        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Halaman Khusus Admin (Kelola User)</a>
    </div>
</div>
@endsection
