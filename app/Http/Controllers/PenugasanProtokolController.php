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
        $month = $request->input('month');
        $year = $request->input('year');

        // Fetch Petugas
        $petugasQuery = MasterPetugasProtokol::query();
        if ($search) {
            $petugasQuery->where('nama', 'like', "%{$search}%");
        }
        $petugasList = $petugasQuery->get()->keyBy('id_petugas');

        // Initialize counts
        foreach ($petugasList as $p) {
            $p->pelayanan_count = 0;
            $p->persidangan_count = 0;
            $p->perjalanan_dinas_count = 0;
            $p->kunjungan_kerja_count = 0;
        }

        // 1. Pelayanan Keprotokolan
        $pelayananQuery = \App\Models\PelayananKeprotokolan::query();
        if ($month) $pelayananQuery->whereMonth('tanggal_kegiatan', $month);
        if ($year) $pelayananQuery->whereYear('tanggal_kegiatan', $year);
        $pelayananTasks = $pelayananQuery->get(['id_petugas']);

        foreach ($pelayananTasks as $task) {
            $ids = is_string($task->id_petugas) ? json_decode($task->id_petugas, true) : $task->id_petugas;
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if (isset($petugasList[$id])) $petugasList[$id]->pelayanan_count++;
                }
            } else if (!is_null($ids)) {
                if (isset($petugasList[$ids])) $petugasList[$ids]->pelayanan_count++;
            }
        }

        // 2. Persidangan
        $persidanganQuery = \App\Models\Persidangan::query();
        if ($month) $persidanganQuery->whereMonth('tanggal_persidangan', $month);
        if ($year) $persidanganQuery->whereYear('tanggal_persidangan', $year);
        $persidanganTasks = $persidanganQuery->get(['id_petugas']);

        foreach ($persidanganTasks as $task) {
            $ids = is_string($task->id_petugas) ? json_decode($task->id_petugas, true) : $task->id_petugas;
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if (isset($petugasList[$id])) $petugasList[$id]->persidangan_count++;
                }
            } else if (!is_null($ids)) {
                if (isset($petugasList[$ids])) $petugasList[$ids]->persidangan_count++;
            }
        }

        // 3. Administrasi Perjalanan Dinas
        $perjalananQuery = \App\Models\AdministrasiPerjalananDinas::query();
        if ($month) $perjalananQuery->whereMonth('tanggal_mulai', $month);
        if ($year) $perjalananQuery->whereYear('tanggal_mulai', $year);
        $perjalananTasks = $perjalananQuery->get(['id_petugas']);

        foreach ($perjalananTasks as $task) {
            $ids = is_string($task->id_petugas) ? json_decode($task->id_petugas, true) : $task->id_petugas;
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if (isset($petugasList[$id])) $petugasList[$id]->perjalanan_dinas_count++;
                }
            } else if (!is_null($ids)) {
                if (isset($petugasList[$ids])) $petugasList[$ids]->perjalanan_dinas_count++;
            }
        }

        // 4. Kunjungan Kerja
        $kunjunganQuery = \App\Models\KunjunganKerja::query();
        if ($month) $kunjunganQuery->whereMonth('tanggal_kunjungan', $month);
        if ($year) $kunjunganQuery->whereYear('tanggal_kunjungan', $year);
        $kunjunganTasks = $kunjunganQuery->get(['id_petugas']);

        foreach ($kunjunganTasks as $task) {
            $ids = is_string($task->id_petugas) ? json_decode($task->id_petugas, true) : $task->id_petugas;
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if (isset($petugasList[$id])) $petugasList[$id]->kunjungan_kerja_count++;
                }
            } else if (!is_null($ids)) {
                if (isset($petugasList[$ids])) $petugasList[$ids]->kunjungan_kerja_count++;
            }
        }

        // Premium Color Palette mapping with Hex Fallbacks
        $colorStyles = [
            ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'bar' => 'from-indigo-500 to-blue-600', 'header' => 'from-indigo-600 to-blue-700', 'hex_from' => '#4f46e5', 'hex_to' => '#1d4ed8'],
            ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'bar' => 'from-emerald-500 to-teal-600', 'header' => 'from-emerald-500 to-teal-600', 'hex_from' => '#10b981', 'hex_to' => '#0d9488'],
            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'bar' => 'from-amber-500 to-orange-600', 'header' => 'from-amber-500 to-orange-600', 'hex_from' => '#f59e0b', 'hex_to' => '#ea580c'],
            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'bar' => 'from-rose-500 to-pink-600', 'header' => 'from-rose-500 to-pink-600', 'hex_from' => '#f43f5e', 'hex_to' => '#db2777'],
            ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'bar' => 'from-violet-500 to-purple-600', 'header' => 'from-violet-500 to-purple-600', 'hex_from' => '#8b5cf6', 'hex_to' => '#7c3aed'],
            ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'bar' => 'from-sky-500 to-indigo-600', 'header' => 'from-sky-500 to-indigo-600', 'hex_from' => '#0ea5e9', 'hex_to' => '#4f46e5'],
        ];

        // Calculate Totals per Petugas
        $petugas = $petugasList->map(function ($p, $index) use ($colorStyles) {
            $p->total_count = $p->pelayanan_count + $p->persidangan_count + $p->perjalanan_dinas_count + $p->kunjungan_kerja_count;
            return $p;
        })
        ->sortByDesc('total_count')
        ->values()
        ->map(function($p, $index) use ($colorStyles) {
            $p->style = $colorStyles[$index % count($colorStyles)];
            return $p;
        });

        // Calculate Totals
        $totalPenugasan = $petugas->sum('total_count');
        $totalPetugas = $petugas->count();

        return view('penugasan-protokol.index', compact('petugas', 'totalPenugasan', 'totalPetugas', 'colorStyles'));
    }

    /**
     * Display the specified resource (AJAX for Detail Card).
     */
    public function show(Request $request, $id)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $petugas = MasterPetugasProtokol::findOrFail($id);

        // Common filter closure
        $matchId = function ($item) use ($id) {
            $ids = is_string($item->id_petugas) ? json_decode($item->id_petugas, true) : $item->id_petugas;
            if (is_array($ids)) return in_array($id, $ids);
            return $ids == $id;
        };

        // 1. Pelayanan
        $pelayananQuery = \App\Models\PelayananKeprotokolan::with('jenisPelayanan');
        if ($month) $pelayananQuery->whereMonth('tanggal_kegiatan', $month);
        if ($year) $pelayananQuery->whereYear('tanggal_kegiatan', $year);
        $pelayananList = $pelayananQuery->get()->filter($matchId);

        // 2. Persidangan
        $persidanganQuery = \App\Models\Persidangan::with('jenisPersidangan');
        if ($month) $persidanganQuery->whereMonth('tanggal_persidangan', $month);
        if ($year) $persidanganQuery->whereYear('tanggal_persidangan', $year);
        $persidanganList = $persidanganQuery->get()->filter($matchId);

        // 3. Perjalanan Dinas
        $perjalananQuery = \App\Models\AdministrasiPerjalananDinas::with('jenisPerjalananDinas');
        if ($month) $perjalananQuery->whereMonth('tanggal_mulai', $month);
        if ($year) $perjalananQuery->whereYear('tanggal_mulai', $year);
        $perjalananList = $perjalananQuery->get()->filter($matchId);

        // 4. Kunjungan Kerja
        $kunjunganQuery = \App\Models\KunjunganKerja::with('jenisKunjungan');
        if ($month) $kunjunganQuery->whereMonth('tanggal_kunjungan', $month);
        if ($year) $kunjunganQuery->whereYear('tanggal_kunjungan', $year);
        $kunjunganList = $kunjunganQuery->get()->filter($matchId);

        $breakdown = collect();

        // Breakdown Pelayanan
        $pelayananList
            ->groupBy(fn($item) => $item->jenisPelayanan->nama_jenis ?? 'Pelayanan Lainnya')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Protokol] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Persidangan
        $persidanganList
            ->groupBy(fn($item) => $item->jenisPersidangan->nama_jenis ?? 'Persidangan Lainnya')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Sidang] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Perjalanan Dinas
        $perjalananList
            ->groupBy(fn($item) => $item->jenisPerjalananDinas->nama_jenis ?? 'Perjalanan Dinas')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Dinas] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        // Breakdown Kunjungan Kerja
        $kunjunganList
            ->groupBy(fn($item) => $item->jenisKunjungan->nama_jenis ?? 'Kunjungan Kerja')
            ->each(function ($items, $key) use (&$breakdown) {
                $breakdown->push([
                    'kategori' => '[Kunker] ' . $key,
                    'jumlah' => $items->count()
                ]);
            });

        $totalCount = $pelayananList->count() + $persidanganList->count() + $perjalananList->count() + $kunjunganList->count();

        // Find the index in the sorted list to match the style
        $search = $request->input('search');
        $petugasQuery = MasterPetugasProtokol::query();
        if ($search) $petugasQuery->where('nama', 'like', "%{$search}%");
        $allSorted = $petugasQuery->get()->map(function($p) use ($month, $year) {
             // Re-calculate sum for sorting consistency if needed, but here we just need index
             return $p;
        })->sortByDesc(fn($p) => 0)->values(); // Simplify: just use the sorted order from index

        // Color Palette mapping (must match index)
        $colorStyles = [
            ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'bar' => 'from-indigo-500 to-blue-600', 'header' => 'from-indigo-600 to-blue-700'],
            ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'bar' => 'from-emerald-500 to-teal-600', 'header' => 'from-emerald-500 to-teal-600'],
            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'bar' => 'from-amber-500 to-orange-600', 'header' => 'from-amber-500 to-orange-600'],
            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'bar' => 'from-rose-500 to-pink-600', 'header' => 'from-rose-500 to-pink-600'],
            ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'bar' => 'from-violet-500 to-purple-600', 'header' => 'from-violet-500 to-purple-600'],
            ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'bar' => 'from-sky-500 to-indigo-600', 'header' => 'from-sky-500 to-indigo-600'],
        ];

        return response()->json([
            'petugas' => [
                'id' => $petugas->id_petugas,
                'nama' => $petugas->nama,
                'initial' => strtoupper(substr($petugas->nama, 0, 1)),
            ],
            'total_penugasan' => $totalCount,
            'breakdown' => $breakdown->values(),
            'style' => $request->input('style') // Pass style forward from client
        ]);
    }
}
