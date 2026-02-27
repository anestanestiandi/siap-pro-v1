<?php

namespace App\Http\Controllers;

use App\Models\MasterAnggotaDewan;
use App\Models\MasterJenisPelayanan;
use App\Models\MasterJenisPersidangan;
use App\Models\MasterJenisKunjungan;
use App\Models\MasterJenisPerjalananDinas;
use App\Models\PelayananKeprotokolan;
use App\Models\Persidangan;
use App\Models\KunjunganKerja;
use App\Models\AdministrasiPerjalananDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isEksternal = $user->role === 'eksternal';

        // Filters
        $filterType = $request->get('filter_type', 'all');
        $sortYear = $request->get('sort_year', 'desc');
        
        $sortOrder = in_array($sortYear, ['asc', 'desc']) ? $sortYear : 'desc';
        $year = is_numeric($sortYear) ? $sortYear : null;

        // 1. Data untuk Bar Chart (Jenis Kegiatan)
        
        // Pelayanan
        $pelayananQuery = MasterJenisPelayanan::query();
        $pelayananQuery->withCount(['pelayananKeprotokolan' => function($q) use ($year) {
            if ($year) $q->whereYear('tanggal_kegiatan', $year);
        }]);
        $jenisPelayanan = $pelayananQuery->get()->map(function($item) {
            return [
                'nama' => $item->nama_jenis, 
                'total' => $item->pelayanan_keprotokolan_count, 
                'kategori' => 'Pelayanan Keprotokolan'
            ];
        });

        // Persidangan
        $persidanganQuery = MasterJenisPersidangan::query();
        $persidanganQuery->withCount(['persidangan' => function($q) use ($year) {
            if ($year) $q->whereYear('tanggal_persidangan', $year);
        }]);
        $jenisPersidangan = $persidanganQuery->get()->map(function($item) {
            return [
                'nama' => $item->nama_jenis, 
                'total' => $item->persidangan_count, 
                'kategori' => 'Persidangan'
            ];
        });

        // Kunjungan Kerja
        $kunjunganQuery = MasterJenisKunjungan::query();
        $kunjunganQuery->withCount(['kunjunganKerja' => function($q) use ($year) {
            if ($year) $q->whereYear('tanggal_kunjungan', $year);
        }]);
        $jenisKunjunganRaw = $kunjunganQuery->get();
        if ($isEksternal) {
            $jenisKunjungan = $jenisKunjunganRaw->groupBy(function($item) {
                if (str_contains(strtolower($item->nama_jenis), 'administrasi')) return 'Administrasi Kunjungan Kerja';
                if (str_contains(strtolower($item->nama_jenis), 'pendampingan')) return 'Pendampingan Kunjungan Kerja';
                return $item->nama_jenis;
            })->map(function($items, $key) {
                return [
                    'nama' => $key,
                    'total' => $items->sum('kunjungan_kerja_count'),
                    'kategori' => 'Kunjungan Kerja'
                ];
            })->values();
        } else {
            $jenisKunjungan = $jenisKunjunganRaw->map(function($item) {
                return [
                    'nama' => $item->nama_jenis, 
                    'total' => $item->kunjungan_kerja_count, 
                    'kategori' => 'Kunjungan Kerja'
                ];
            });
        }

        // Perjalanan Dinas
        $perjalananQuery = MasterJenisPerjalananDinas::query();
        $perjalananQuery->withCount(['admPerjalananDinas' => function($q) use ($year) {
            if ($year) $q->whereYear('tanggal_mulai', $year);
        }]);
        $jenisPerjalanan = $perjalananQuery->get()->map(function($item) {
            return [
                'nama' => $item->nama_jenis, 
                'total' => $item->adm_perjalanan_dinas_count, 
                'kategori' => 'Administrasi Perjalanan Dinas'
            ];
        });

        $allJenis = collect($jenisPelayanan)->concat($jenisPersidangan)->concat($jenisKunjungan)->concat($jenisPerjalanan);

        // Filter Type
        if ($filterType !== 'all') {
            $allJenis = $allJenis->where('kategori', $filterType);
        }

        // Sorting
        if ($sortOrder === 'desc') {
            $allJenis = $allJenis->sortByDesc('total');
        } else {
            $allJenis = $allJenis->sortBy('total');
        }

        $allJenis = $allJenis->values();
        $totalKegiatan = $allJenis->sum('total');

        // 2. Data untuk Anggota Dewan
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->get()->map(function($anggota) use ($year, $isEksternal) {
            // Pelayanan
            $pelayanan = $anggota->pelayananKeprotokolan()
                ->when($year, fn($q) => $q->whereYear('tanggal_kegiatan', $year))
                ->with('jenisPelayanan')
                ->get()
                ->groupBy('jenisPelayanan.nama_jenis')
                ->map(fn($group, $label) => ['label' => $label, 'value' => $group->count()]);

            // Persidangan
            $persidangan = $anggota->persidangan()
                ->when($year, fn($q) => $q->whereYear('tanggal_persidangan', $year))
                ->with('jenisPersidangan')
                ->get()
                ->groupBy('jenisPersidangan.nama_jenis')
                ->map(fn($group, $label) => ['label' => $label, 'value' => $group->count()]);

            // Kunjungan Kerja
            $kunker = $anggota->kunjunganKerja()
                ->when($year, fn($q) => $q->whereYear('tanggal_kunjungan', $year))
                ->with('jenisKunjungan')
                ->get()
                ->groupBy(function($item) use ($isEksternal) {
                    $label = $item->jenisKunjungan->nama_jenis;
                    if ($isEksternal) {
                        if (str_contains(strtolower($label), 'administrasi')) return 'Administrasi Kunjungan Kerja';
                        if (str_contains(strtolower($label), 'pendampingan')) return 'Pendampingan Kunjungan Kerja';
                    }
                    return $label;
                })
                ->map(fn($group, $label) => ['label' => $label, 'value' => $group->count()]);

            // Perjalanan Dinas (Fallback search by name)
            $perjalanan = DB::table('administrasi_perjalanan_dinas')
                ->join('master_jenis_perjalanan_dinas', 'administrasi_perjalanan_dinas.id_jenis_perjalanan_dinas', '=', 'master_jenis_perjalanan_dinas.id_jenis_perjalanan')
                ->where('administrasi_perjalanan_dinas.pelaksana', 'like', '%' . $anggota->nama . '%')
                ->when($year, fn($q) => $q->whereYear('administrasi_perjalanan_dinas.tanggal_mulai', $year))
                ->select('master_jenis_perjalanan_dinas.nama_jenis as label', DB::raw('count(*) as value'))
                ->groupBy('master_jenis_perjalanan_dinas.nama_jenis')
                ->get()
                ->mapWithKeys(fn($item) => [$item->label => ['label' => $item->label, 'value' => $item->value]]);

            // Combine all
            $breakdown = collect($pelayanan)
                ->concat($persidangan)
                ->concat($kunker)
                ->concat($perjalanan)
                ->values()
                ->toArray();
            
            $total = collect($breakdown)->sum('value');

            return [
                'nama' => $anggota->nama,
                'total' => $total,
                'tipe_count' => count($breakdown),
                'breakdown' => $breakdown
            ];
        })->filter(function($d) { return $d['total'] > 0; })->sortByDesc('total')->take(8)->values();

        return view('dashboard', compact('allJenis', 'totalKegiatan', 'anggotaDewan'));
    }
}
