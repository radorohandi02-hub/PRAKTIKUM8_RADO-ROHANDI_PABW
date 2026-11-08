<?php

namespace Modules\Peminjaman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Peminjaman\Http\Requests\PeminjamanRequest;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('peminjaman::index');
    }

    public function data(): JsonResponse
    {
        $items = Peminjaman::latest()->get()->map(fn ($p) => $this->format($p));

        return response()->json(['data' => $items]);
    }

    public function show(Peminjaman $peminjaman): JsonResponse
    {
        return response()->json(['data' => $this->format($peminjaman)]);
    }

    public function store(PeminjamanRequest $request): JsonResponse
    {
        $data = $request->safe()->except('bukti');

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti-peminjaman', 'public');
        }

        $peminjaman = Peminjaman::create($data);

        return response()->json([
            'message' => 'Data peminjaman berhasil ditambahkan.',
            'data' => $this->format($peminjaman),
        ], 201);
    }

    public function update(PeminjamanRequest $request, Peminjaman $peminjaman): JsonResponse
    {
        $data = $request->safe()->except('bukti');

        if ($request->hasFile('bukti')) {
            // ganti berkas: hapus yang lama dari storage
            if ($peminjaman->bukti) {
                Storage::disk('public')->delete($peminjaman->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('bukti-peminjaman', 'public');
        }

        $peminjaman->update($data);

        return response()->json([
            'message' => 'Data peminjaman berhasil diubah.',
            'data' => $this->format($peminjaman->fresh()),
        ]);
    }

    public function destroy(Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->bukti) {
            Storage::disk('public')->delete($peminjaman->bukti);
        }

        $peminjaman->delete();

        return response()->json(['message' => 'Data peminjaman berhasil dihapus.']);
    }

    private function format(Peminjaman $p): array
    {
        return [
            'id' => $p->id,
            'nama_peminjam' => $p->nama_peminjam,
            'judul_buku' => $p->judul_buku,
            'tanggal_pinjam' => $p->tanggal_pinjam->format('Y-m-d'),
            'tanggal_kembali' => $p->tanggal_kembali->format('Y-m-d'),
            'status' => $p->status,
            'bukti' => $p->bukti,
            'bukti_url' => $p->bukti ? asset('storage/'.$p->bukti) : null,
        ];
    }
}
