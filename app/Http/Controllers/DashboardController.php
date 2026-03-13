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
        $pelayananQuery->withCount(['pelayananKeprotokolan' => function ($q) use ($year) {
            if ($year)
                $q->whereYear('tanggal_kegiatan', $year);
        }]);
        $jenisPelayanan = $pelayananQuery->get()->map(function ($item) {
            return [
            'nama' => $item->nama_jenis,
            'total' => $item->pelayanan_keprotokolan_count,
            'kategori' => 'Pelayanan Keprotokolan'
            ];
        });

        // Persidangan
        $persidanganQuery = MasterJenisPersidangan::query();
        $persidanganQuery->withCount(['persidangan' => function ($q) use ($year) {
            if ($year)
                $q->whereYear('tanggal_persidangan', $year);
        }]);
        $jenisPersidangan = $persidanganQuery->get()->map(function ($item) {
            return [
            'nama' => $item->nama_jenis,
            'total' => $item->persidangan_count,
            'kategori' => 'Persidangan'
            ];
        });

        // Kunjungan Kerja
        $kunjunganQuery = MasterJenisKunjungan::query();
        $kunjunganQuery->withCount(['kunjunganKerja' => function ($q) use ($year) {
            if ($year)
                $q->whereYear('tanggal_kunjungan', $year);
        }]);
        $jenisKunjunganRaw = $kunjunganQuery->get();
        if ($isEksternal) {
            $jenisKunjungan = $jenisKunjunganRaw->filter(function ($item) {
                $name = strtolower($item->nama_jenis);
                if (str_contains($name, 'pendampingan'))
                    return false;
                return true;
            })->map(function ($item) {
                $name = strtolower($item->nama_jenis);
                $displayName = $item->nama_jenis;
                if (str_contains($name, 'luar negeri')) {
                    $displayName = 'Kunjungan Kerja Luar Negeri';
                }
                elseif (str_contains($name, 'dalam negeri')) {
                    $displayName = 'Kunjungan Kerja Dalam Negeri';
                }

                return [
                'nama' => $displayName,
                'total' => $item->kunjungan_kerja_count,
                'kategori' => 'Kunjungan Kerja'
                ];
            })->values();
        }
        else {
            $jenisKunjungan = $jenisKunjunganRaw->map(function ($item) {
                return [
                'nama' => $item->nama_jenis,
                'total' => $item->kunjungan_kerja_count,
                'kategori' => 'Kunjungan Kerja'
                ];
            });
        }

        // Perjalanan Dinas
        $perjalananQuery = MasterJenisPerjalananDinas::query();
        $perjalananQuery->withCount(['admPerjalananDinas' => function ($q) use ($year) {
            if ($year)
                $q->whereYear('tanggal_mulai', $year);
        }]);
        $jenisPerjalanan = $perjalananQuery->get()->map(function ($item) {
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
        }
        else {
            $allJenis = $allJenis->sortBy('total');
        }

        $allJenis = $allJenis->values();
        $totalKegiatan = $allJenis->sum('total');

        // 2. Data Kegiatan Hari Ini/Besok (Standardized logic)
        $dateFilter = request('date_filter', 'today');
        $targetDate = $dateFilter === 'tomorrow' ? now()->addDay()->startOfDay() : now()->startOfDay();
        $dateTitle = $dateFilter === 'tomorrow' ? 'Agenda Besok' : 'Agenda Hari Ini';

        $rawTodayEvents = collect();

        // Pelayanan
        $rawTodayEvents = $rawTodayEvents->concat(
            PelayananKeprotokolan::with(['jenisPelayanan'])
            ->whereDate('tanggal_kegiatan', $targetDate)
            ->get()
            ->map(fn($item) => [
        'title' => $item->nama_kegiatan,
        'time' => $item->waktu ?\Carbon\Carbon::parse($item->waktu)->format('H:i') : '—',
        'date' => $item->tanggal_kegiatan,
        'location' => $item->tempat,
        'category' => 'Pelayanan',
        'status' => 'Upcoming',
        'attendees' => $item->anggotaDewan->pluck('nama')->toArray()
        ])
        );

        // Persidangan
        $rawTodayEvents = $rawTodayEvents->concat(
            Persidangan::with(['jenisPersidangan'])
            ->whereDate('tanggal_persidangan', $targetDate)
            ->get()
            ->map(fn($item) => [
        'title' => $item->nama_persidangan,
        'time' => $item->waktu ?\Carbon\Carbon::parse($item->waktu)->format('H:i') : '—',
        'date' => $item->tanggal_persidangan,
        'location' => $item->tempat,
        'category' => 'Persidangan',
        'status' => 'Upcoming',
        'attendees' => $item->anggotaDewan->pluck('nama')->toArray()
        ])
        );

        $rawTodayEvents = $rawTodayEvents->concat(
            KunjunganKerja::with(['provinsi'])
            ->whereDate('tanggal_kunjungan', '<=', $targetDate)
            ->whereRaw('DATE(COALESCE(tanggal_selesai, tanggal_kunjungan)) >= ?', [$targetDate->format('Y-m-d')])
            ->get()
            ->map(fn($item) => [
        'title' => $item->nama_kegiatan,
        'time' => $item->waktu ?\Carbon\Carbon::parse($item->waktu)->format('H:i') : '—',
        'date' => $item->tanggal_kunjungan,
        'location' => $item->tipe_tujuan == 'dalam_negeri' ? ($item->provinsi->nama_provinsi ?? 'Dalam Negeri') : $item->tujuan_luar_negeri,
        'category' => 'Kunjungan Kerja',
        'status' => 'Upcoming',
        'attendees' => $item->anggotaDewan->pluck('nama')->toArray()
        ])
        );

        $now = now();
        $todayEvents = $rawTodayEvents->map(function ($event) use ($now, $targetDate) {
            $eventTime = null;
            if ($event['time'] !== '—') {
                try {
                    $eventTime = \Carbon\Carbon::parse($targetDate->format('Y-m-d') . ' ' . $event['time']);
                }
                catch (\Exception $e) {
                    $eventTime = null;
                }
            }
            $event['is_past'] = $eventTime ? $eventTime->isPast() : false;
            return $event;
        })
            ->sort(function ($a, $b) {
            if ($a['is_past'] === $b['is_past']) {
                return strcmp($a['time'], $b['time']);
            }
            return $a['is_past'] <=> $b['is_past'];
        })
            ->values();

        // 3. Data untuk Anggota Dewan
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->get()->map(function ($anggota) use ($year, $isEksternal) {
            // Pelayanan
            $pelayanan = PelayananKeprotokolan::whereJsonContains('id_anggota', (string)$anggota->id_anggota)
                ->when($year, fn($q) => $q->whereYear('tanggal_kegiatan', $year))
                ->with('jenisPelayanan')
                ->get()
                ->groupBy('jenisPelayanan.nama_jenis')
                ->map(fn($group, $label) => ['label' => $label, 'value' => $group->count()]);

            // Persidangan
            $persidangan = Persidangan::whereJsonContains('id_anggota', (string)$anggota->id_anggota)
                ->when($year, fn($q) => $q->whereYear('tanggal_persidangan', $year))
                ->with('jenisPersidangan')
                ->get()
                ->groupBy('jenisPersidangan.nama_jenis')
                ->map(fn($group, $label) => ['label' => $label, 'value' => $group->count()]);

            // Kunjungan Kerja
            $kunker = KunjunganKerja::whereJsonContains('id_anggota', (string)$anggota->id_anggota)
                ->when($year, fn($q) => $q->whereYear('tanggal_kunjungan', $year))
                ->with('jenisKunjungan')
                ->get()
                ->filter(function ($item) use ($isEksternal) {
                if ($isEksternal) {
                    $label = strtolower($item->jenisKunjungan->nama_jenis);
                    if (str_contains($label, 'pendampingan'))
                        return false;
                }
                return true;
            }
            )
                ->groupBy(function ($item) use ($isEksternal) {
                $label = $item->jenisKunjungan->nama_jenis;
                if ($isEksternal) {
                    $lowerLabel = strtolower($label);
                    if (str_contains($lowerLabel, 'luar negeri'))
                        return 'Kunjungan Kerja Luar Negeri';
                    if (str_contains($lowerLabel, 'dalam negeri'))
                        return 'Kunjungan Kerja Dalam Negeri';
                }
                return $label;
            }
            )
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
        })->filter(function ($d) {
            return $d['total'] > 0;
        })->sortByDesc('total')->take(8)->values();

        return view('dashboard', compact(
            'allJenis',
            'totalKegiatan',
            'anggotaDewan',
            'todayEvents',
            'dateTitle',
            'dateFilter'
        ));
    }
}
