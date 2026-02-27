<?php

namespace App\Http\Controllers;

use App\Models\MasterPetugasProtokol;
use Illuminate\Http\Request;

class PenugasanProtokolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $petugas = MasterPetugasProtokol::withCount(['pelayanan', 'persidangan', 'perjalananDinas', 'kunjunganKerja'])
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->get()
            ->map(function ($p) {
                $p->total_count = $p->pelayanan_count + $p->persidangan_count + $p->perjalanan_dinas_count + $p->kunjungan_kerja_count;
                return $p;
            })
            ->sortByDesc('total_count')
            ->values();

        // Calculate Totals
        $totalPenugasan = $petugas->sum('total_count');
        $totalPetugas = $petugas->count();

        return view('penugasan-protokol.index', compact('petugas', 'totalPenugasan', 'totalPetugas'));
    }

    /**
     * Display the specified resource (AJAX for Detail Card).
     */
    public function show($id)
    {
        $petugas = MasterPetugasProtokol::with([
            'pelayanan.jenisPelayanan',
            'persidangan.jenisPersidangan',
            'perjalananDinas.jenisPerjalananDinas',
            'kunjunganKerja.jenisKunjungan'
        ])->findOrFail($id);

        $breakdown = collect();

        // Breakdown Pelayanan
        $petugas->pelayanan
            ->groupBy(fn($item) => $item->jenisPelayanan->nama_jenis ?? 'Pelayanan Lainnya')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Protokol] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Persidangan
        $petugas->persidangan
            ->groupBy(fn($item) => $item->jenisPersidangan->nama_jenis ?? 'Persidangan Lainnya')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Sidang] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Perjalanan Dinas
        $petugas->perjalananDinas
            ->groupBy(fn($item) => $item->jenisPerjalananDinas->nama_jenis ?? 'Perjalanan Dinas')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Dinas] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Kunjungan Kerja
        $petugas->kunjunganKerja
            ->groupBy(fn($item) => $item->jenisKunjungan->nama_jenis ?? 'Kunjungan Kerja')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Kunker] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        $totalCount = $petugas->pelayanan->count() + $petugas->persidangan->count() + $petugas->perjalananDinas->count() + $petugas->kunjunganKerja->count();

        return response()->json([
            'petugas' => [
                'id' => $petugas->id_petugas,
                'nama' => $petugas->nama,
                'initial' => strtoupper(substr($petugas->nama, 0, 1)),
            ],
            'total_penugasan' => $totalCount,
            'breakdown' => $breakdown->values()
        ]);
    }
}
