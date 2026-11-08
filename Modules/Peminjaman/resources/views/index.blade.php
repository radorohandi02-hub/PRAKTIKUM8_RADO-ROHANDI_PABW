@extends('layouts.app')
@section('title', 'Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Peminjaman</h3>
    <button class="btn btn-primary" id="btn-tambah">+ Tambah Peminjaman</button>
</div>

<div id="alert-box"></div>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle bg-white">
    <thead class="table-primary">
        <tr>
            <th style="width:50px">#</th><th>Nama Peminjam</th><th>Judul Buku</th>
            <th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Bukti</th>
            <th style="width:150px">Aksi</th>
        </tr>
    </thead>
    <tbody id="tbody"><tr><td colspan="8" class="text-center text-muted">Memuat data...</td></tr></tbody>
</table>
</div>

{{-- Modal tambah / ubah --}}
<div class="modal fade" id="modal-form" tabindex="-1">
    <div class="modal-dialog">
        <form id="form-peminjaman" class="modal-content" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" id="f-nama_peminjam" class="form-control">
                    <div class="invalid-feedback" id="err-nama_peminjam"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul_buku" id="f-judul_buku" class="form-control">
                    <div class="invalid-feedback" id="err-judul_buku"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="f-tanggal_pinjam" class="form-control">
                        <div class="invalid-feedback" id="err-tanggal_pinjam"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="f-tanggal_kembali" class="form-control">
                        <div class="invalid-feedback" id="err-tanggal_kembali"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="f-status" class="form-select">
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                    <div class="invalid-feedback" id="err-status"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bukti Peminjaman (jpg/png/pdf, maks 2 MB)</label>
                    <input type="file" name="bukti" id="f-bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    <div class="invalid-feedback" id="err-bukti"></div>
                    <div class="form-text" id="bukti-lama"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const urls = {
    data: "{{ route('peminjaman.data') }}",
    store: "{{ route('peminjaman.store') }}",
    base: "{{ url('peminjaman') }}",
};
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const fields = ['nama_peminjam', 'judul_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status', 'bukti'];
const modal = new bootstrap.Modal(document.getElementById('modal-form'));
const form = document.getElementById('form-peminjaman');
const tbody = document.getElementById('tbody');
let editId = null;

const baseHeaders = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };

function esc(s) {
    const d = document.createElement('div');
    d.textContent = s ?? '';
    return d.innerHTML;
}

function showAlert(msg, type = 'success') {
    const box = document.getElementById('alert-box');
    box.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${esc(msg)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
}

function clearErrors() {
    fields.forEach(f => {
        document.getElementById('f-' + f).classList.remove('is-invalid');
        document.getElementById('err-' + f).textContent = '';
    });
}

async function loadData() {
    const res = await fetch(urls.data, { headers: baseHeaders });
    const json = await res.json();
    if (!json.data.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Belum ada data peminjaman.</td></tr>';
        return;
    }
    tbody.innerHTML = json.data.map((p, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${esc(p.nama_peminjam)}</td>
            <td>${esc(p.judul_buku)}</td>
            <td>${esc(p.tanggal_pinjam)}</td>
            <td>${esc(p.tanggal_kembali)}</td>
            <td><span class="badge ${p.status === 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success'}">${esc(p.status)}</span></td>
            <td>${p.bukti_url ? `<a href="${p.bukti_url}" target="_blank">Buka berkas</a>` : '-'}</td>
            <td>
                <button class="btn btn-sm btn-warning btn-edit" data-id="${p.id}">Ubah</button>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="${p.id}">Hapus</button>
            </td>
        </tr>`).join('');
}

document.getElementById('btn-tambah').addEventListener('click', () => {
    editId = null;
    form.reset();
    clearErrors();
    document.getElementById('bukti-lama').innerHTML = '';
    document.getElementById('modal-title').textContent = 'Tambah Peminjaman';
    modal.show();
});

tbody.addEventListener('click', async (e) => {
    const editBtn = e.target.closest('.btn-edit');
    const delBtn = e.target.closest('.btn-hapus');

    if (editBtn) {
        const id = editBtn.dataset.id;
        const res = await fetch(`${urls.base}/${id}`, { headers: baseHeaders });
        const { data } = await res.json();
        editId = id;
        form.reset();
        clearErrors();
        ['nama_peminjam', 'judul_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status'].forEach(f => {
            document.getElementById('f-' + f).value = data[f];
        });
        document.getElementById('bukti-lama').innerHTML = data.bukti_url
            ? `Berkas saat ini: <a href="${data.bukti_url}" target="_blank">buka berkas</a>. Pilih file baru untuk menggantinya.`
            : 'Belum ada berkas.';
        document.getElementById('modal-title').textContent = 'Ubah Peminjaman';
        modal.show();
    }

    if (delBtn) {
        if (!confirm('Hapus data peminjaman ini?')) return;
        const res = await fetch(`${urls.base}/${delBtn.dataset.id}`, {
            method: 'DELETE',
            headers: { ...baseHeaders, 'X-CSRF-TOKEN': csrf },
        });
        const json = await res.json();
        showAlert(json.message || 'Terjadi kesalahan.', res.ok ? 'success' : 'danger');
        if (res.ok) loadData();
    }
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const fd = new FormData(form);
    let url = urls.store;
    if (editId) {
        url = `${urls.base}/${editId}`;
        fd.append('_method', 'PUT'); // multipart harus POST + method spoofing
    }

    const btn = document.getElementById('btn-simpan');
    btn.disabled = true;
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { ...baseHeaders, 'X-CSRF-TOKEN': csrf },
            body: fd,
        });
        const json = await res.json();

        if (res.status === 422) {
            Object.entries(json.errors).forEach(([field, msgs]) => {
                const input = document.getElementById('f-' + field);
                if (input) {
                    input.classList.add('is-invalid');
                    document.getElementById('err-' + field).textContent = msgs[0];
                }
            });
            return;
        }
        if (!res.ok) {
            showAlert(json.message || 'Terjadi kesalahan.', 'danger');
            return;
        }

        modal.hide();
        showAlert(json.message);
        loadData();
    } finally {
        btn.disabled = false;
    }
});

loadData();
</script>
@endpush
