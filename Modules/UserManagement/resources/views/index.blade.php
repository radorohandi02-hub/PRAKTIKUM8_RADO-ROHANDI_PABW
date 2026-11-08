@extends('layouts.app')
@section('title', 'Kelola User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Kelola User</h3>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
</div>

<form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama atau email...">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Cari</button>
        @if($q !== '')<a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>@endif
    </div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle bg-white">
    <thead class="table-primary">
        <tr><th style="width:50px">#</th><th>Nama</th><th>Email</th><th>Role</th><th style="width:170px">Aksi</th></tr>
    </thead>
    <tbody>
    @forelse($users as $i => $u)
        <tr>
            <td>{{ $users->firstItem() + $i }}</td>
            <td>{{ $u->name }} @if($u->id === auth()->id())<span class="badge bg-secondary">Anda</span>@endif</td>
            <td>{{ $u->email }}</td>
            <td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-info text-dark' }}">{{ $u->role }}</span></td>
            <td>
                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">Ubah</a>
                @if($u->id !== auth()->id())
                    <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus user ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted">Data user tidak ditemukan.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{ $users->links('pagination::bootstrap-5') }}
@endsection
