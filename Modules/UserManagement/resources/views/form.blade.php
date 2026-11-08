@extends('layouts.app')
@php $isEdit = $user->exists; @endphp
@section('title', $isEdit ? 'Ubah User' : 'Tambah User')
@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">{{ $isEdit ? 'Ubah User' : 'Tambah User' }}</h4>
        <form action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" method="POST" novalidate>
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password @if($isEdit)<small class="text-muted">(kosongkan jika tidak diganti)</small>@endif</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    @foreach(['admin' => 'Admin', 'user' => 'User'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('role', $user->role) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
