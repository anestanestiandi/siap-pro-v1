<?php

namespace App\Http\Controllers;

use App\Models\KunjunganKerja;
use App\Models\MasterAnggotaDewan;
use App\Models\MasterJenisKunjungan;
use App\Models\MasterPetugasProtokol;
use App\Models\MasterProvinsi;
use App\Models\MasterPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KunjunganKerjaController extends Controller
{
    /**
     * Tampilkan daftar kegiatan kunjungan kerja.
     */
    public function index(Request $request)
    {
        // 1. Initial Query
        $query = KunjunganKerja::with(['jenisKunjungan', 'provinsi']);

        // 2. Define Filter Logic (Closure for reusability)
        $applyFilter = function ($q) use ($request) {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $q->whereDate('tanggal_kunjungan', '<=', $request->end_date)
                    ->whereRaw('DATE(COALESCE(tanggal_selesai, tanggal_kunjungan)) >= ?', [$request->start_date]);
            } elseif ($request->filled('month')) {
                try {
                    $date = \Carbon\Carbon::parse($request->month);
                    $q->whereYear('tanggal_kunjungan', $date->year)
                        ->whereMonth('tanggal_kunjungan', $date->month);
                } catch (\Exception $e) {
                }
            } elseif ($request->filled('year')) {
                $q->whereYear('tanggal_kunjungan', $request->year);
            }

            // Search Logic
            if ($request->filled('search')) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('tujuan_luar_negeri', 'like', "%{$search}%")
                        ->orWhereHas('jenisKunjungan', function ($q) use ($search) {
                            $q->where('nama_jenis', 'like', "%{$search}%");
                        })
                        ->orWhereHas('provinsi', function ($q) use ($search) {
                            $q->where('nama_provinsi', 'like', "%{$search}%");
                        });

                    // Search names in JSON field id_anggota
                    $matchingAnggotaIds = MasterAnggotaDewan::where('nama', 'like', "%{$search}%")
                        ->pluck('id_anggota')->toArray();
                    foreach ($matchingAnggotaIds as $id) {
                        $sub->orWhereJsonContains('id_anggota', (string)$id)
                            ->orWhereJsonContains('id_anggota', (int)$id);
                    }

                    // Search names in JSON field id_petugas
                    $matchingPetugasIds = MasterPetugasProtokol::where('nama', 'like', "%{$search}%")
                        ->pluck('id_petugas')->toArray();
                    foreach ($matchingPetugasIds as $id) {
                        $sub->orWhereJsonContains('id_petugas', (string)$id)
                            ->orWhereJsonContains('id_petugas', (int)$id);
                    }

                    // Search in rombongan (JSON array of strings)
                    $sub->orWhere('rombongan', 'like', "%{$search}%");
                });
            }
        };

        // Apply Filter to Main Query
        $applyFilter($query);

        // 3. Summary Cards Data (Filtered)
        $totalKegiatan = $query->count();

        $jenisKunjunganSummaryRaw = MasterJenisKunjungan::withCount([
            'kunjunganKerja as total' => function ($q) use ($applyFilter) {
                $applyFilter($q);
            }
        ])->get();

        $isEksternal = Auth::user()->role === 'eksternal';

        if ($isEksternal) {
            $jenisKunjunganSummary = $jenisKunjunganSummaryRaw->filter(function ($jenis) {
                $name = strtolower($jenis->nama_jenis);
                if (str_contains($name, 'pendampingan')) return false;
                return true;
            })->map(function ($jenis) {
                $name = strtolower($jenis->nama_jenis);
                
                $key = $jenis->nama_jenis;
                if (str_contains($name, 'luar negeri')) {
                    $key = 'Kunjungan Kerja Luar Negeri';
                } elseif (str_contains($name, 'dalam negeri')) {
                    $key = 'Kunjungan Kerja Dalam Negeri';
                }

                $totalCount = $jenis->total;
                
                // Color Style based on type
                $style = ['bg' => 'bg-gradient-to-br from-blue-50 to-blue-100', 'border' => 'border-blue-200', 'icon_bg' => 'from-blue-500 to-blue-600', 'shadow' => 'shadow-blue-500/30', 'text' => 'text-blue-600', 'chip_bg' => 'bg-blue-100']; // Domestic Blue
                if (str_contains(strtolower($key), 'luar negeri')) {
                    $style = ['bg' => 'bg-gradient-to-br from-red-50 to-red-100', 'border' => 'border-red-200', 'icon_bg' => 'from-red-500 to-red-600', 'shadow' => 'shadow-red-500/30', 'text' => 'text-red-600', 'chip_bg' => 'bg-red-100']; // Foreign Red
                }

                return (object) [
                    'nama_jenis' => $key,
                    'total' => $totalCount,
                    'label' => str_contains(strtolower($key), 'luar negeri') ? 'Luar Negeri' : 'Dalam Negeri',
                    'style' => $style
                ];
            })->values();
        } else {
            $jenisKunjunganSummary = $jenisKunjunganSummaryRaw->map(function ($jenis) {
                $colors = [
                    ['bg' => 'bg-gradient-to-br from-blue-50 to-blue-100', 'border' => 'border-blue-200', 'icon_bg' => 'from-blue-500 to-blue-600', 'shadow' => 'shadow-blue-500/30', 'text' => 'text-blue-600', 'chip_bg' => 'bg-blue-100'],
                    ['bg' => 'bg-gradient-to-br from-red-50 to-red-100', 'border' => 'border-red-200', 'icon_bg' => 'from-red-500 to-red-600', 'shadow' => 'shadow-red-500/30', 'text' => 'text-red-600', 'chip_bg' => 'bg-red-100'],
                    ['bg' => 'bg-gradient-to-br from-green-50 to-green-100', 'border' => 'border-green-200', 'icon_bg' => 'from-green-500 to-green-600', 'shadow' => 'shadow-green-500/30', 'text' => 'text-green-600', 'chip_bg' => 'bg-green-100'],
                    ['bg' => 'bg-gradient-to-br from-teal-50 to-teal-100', 'border' => 'border-teal-200', 'icon_bg' => 'from-teal-500 to-teal-600', 'shadow' => 'shadow-teal-500/30', 'text' => 'text-teal-600', 'chip_bg' => 'bg-teal-100'],
                ];
                
                $colorIndex = ($jenis->id_jenis_kunjungan - 1) % count($colors);
                $jenis->style = $colors[$colorIndex];
                
                // Simplified label
                $jenis->label = str_replace(['Administrasi Kunjungan Kerja ', 'Pendampingan Kunjungan Kerja '], '', $jenis->nama_jenis);
                
                return $jenis;
            });
        }

        $totalProtokol = MasterPetugasProtokol::count();

        // 4. Sorting
        $query->orderBy('tanggal_kunjungan', 'desc')->orderBy('waktu', 'desc');

        // 5. Export Logic
        if ($request->has('export')) {
            $kunjungan = $query->get();
            $columns = $request->input('columns', []);
            
            if (empty($columns)) {
                $columns = ['tanggal', 'waktu', 'nama_kegiatan', 'jenis_kunjungan', 'anggota_dewan', 'rombongan', 'tujuan'];
            }

            if ($request->export == 'excel') {
                $fileName = 'laporan-kunjungan-kerja-' . now()->format('Y-m-d') . '.xls';
                return response(view('kunjungan-kerja.excel', compact('kunjungan', 'columns')))
                    ->header('Content-Type', 'application/vnd.ms-excel')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            } 
            
            if ($request->export == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kunjungan-kerja.pdf', compact('kunjungan', 'columns'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('laporan-kunjungan-kerja.pdf');
            }
        }

        // 6. Pagination
        $kunjungan = $query->paginate(10)->onEachSide(1)->withQueryString();

        return view('kunjungan-kerja.index', compact(
            'kunjungan',
            'totalKegiatan',
            'totalProtokol',
            'jenisKunjunganSummary'
        ));
    }

    /**
     * Tampilkan form tambah kegiatan baru.
     */
    public function create()
    {
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisKunjungan = MasterJenisKunjungan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();
        $provinsi = MasterProvinsi::where('is_active', 1)->orderBy('nama_provinsi')->get();
        $masterPegawai = MasterPegawai::where('is_active', 1)->orderBy('nama_lengkap')->get();

        return view('kunjungan-kerja.create', compact(
            'anggotaDewan',
            'jenisKunjungan',
            'petugasProtokol',
            'provinsi',
            'masterPegawai'
        ));
    }

    /**
     * Simpan kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|string',
            'waktu_mulai' => 'nullable',
            'id_jenis_kunjungan' => 'required|exists:master_jenis_kunjungan,id_jenis_kunjungan',
            'tipe_tujuan' => 'required|in:dalam_negeri,luar_negeri',
            'id_provinsi' => 'required_if:tipe_tujuan,dalam_negeri|nullable|exists:master_provinsi,id_provinsi',
            'tujuan_luar_negeri' => 'required_if:tipe_tujuan,luar_negeri|nullable|string|max:255',
            'anggota_dewan_id' => 'required|array',
            'anggota_dewan_id.*' => 'exists:master_anggota_dewan,id_anggota',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'rombongan' => 'nullable|array|max:100',
            'rombongan.*' => 'nullable|string|max:255',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ]);

        // Clean up empty strings in rombongan array
        if (isset($validated['rombongan'])) {
            $validated['rombongan'] = array_filter($validated['rombongan'], fn($val) => !empty(trim($val)));
        }

        $dates = $this->parseDateRange($validated['tanggal']);

        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('file_pendukung')) {
                $filePath = $request->file('file_pendukung')->store('kunjungan-kerja', 'public');
            }

            $kunjungan = KunjunganKerja::create([
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'tanggal_kunjungan' => $dates['start'],
                'tanggal_selesai' => $dates['end'],
                'waktu' => $validated['waktu_mulai'] ?? null,
                'id_jenis_kunjungan' => $validated['id_jenis_kunjungan'],
                'tipe_tujuan' => $validated['tipe_tujuan'],
                'id_provinsi' => $validated['tipe_tujuan'] == 'dalam_negeri' ? $validated['id_provinsi'] : null,
                'tujuan_luar_negeri' => $validated['tipe_tujuan'] == 'luar_negeri' ? ($validated['tujuan_luar_negeri'] ?? null) : null,
                'id_anggota' => $validated['anggota_dewan_id'] ?? [],
                'id_petugas' => $validated['petugas_id'] ?? [],
                'rombongan' => $validated['rombongan'] ?? [],
                'file_path' => $filePath,
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kunjungan-kerja')->with('success', 'Kegiatan kunjungan kerja berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail kegiatan.
     */
    public function show($id)
    {
        $item = KunjunganKerja::with(['jenisKunjungan', 'provinsi', 'creator', 'historyLogs.user'])
            ->findOrFail($id);

        return view('kunjungan-kerja.show', compact('item'));
    }

    /**
     * Tampilkan form edit kegiatan.
     */
    public function edit($id)
    {
        $item = KunjunganKerja::findOrFail($id);
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisKunjungan = MasterJenisKunjungan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();
        $provinsi = MasterProvinsi::where('is_active', 1)->orderBy('nama_provinsi')->get();
        $masterPegawai = MasterPegawai::where('is_active', 1)->orderBy('nama_lengkap')->get();

        return view('kunjungan-kerja.edit', compact(
            'item',
            'anggotaDewan',
            'jenisKunjungan',
            'petugasProtokol',
            'provinsi',
            'masterPegawai'
        ));
    }

    /**
     * Simpan perubahan kegiatan ke database.
     */
    public function update(Request $request, $id)
    {
        $kunjungan = KunjunganKerja::findOrFail($id);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|string',
            'waktu_mulai' => 'nullable',
            'id_jenis_kunjungan' => 'required|exists:master_jenis_kunjungan,id_jenis_kunjungan',
            'tipe_tujuan' => 'required|in:dalam_negeri,luar_negeri',
            'id_provinsi' => 'required_if:tipe_tujuan,dalam_negeri|nullable|exists:master_provinsi,id_provinsi',
            'tujuan_luar_negeri' => 'required_if:tipe_tujuan,luar_negeri|nullable|string|max:255',
            'anggota_dewan_id' => 'required|array',
            'anggota_dewan_id.*' => 'exists:master_anggota_dewan,id_anggota',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'rombongan' => 'nullable|array|max:100',
            'rombongan.*' => 'nullable|string|max:255',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ]);

        // Clean up empty strings in rombongan array
        if (isset($validated['rombongan'])) {
            $validated['rombongan'] = array_filter($validated['rombongan'], fn($val) => !empty(trim($val)));
        }

        $dates = $this->parseDateRange($validated['tanggal']);

        DB::beginTransaction();
        try {
            $filePath = $kunjungan->file_path;
            if ($request->hasFile('file_pendukung')) {
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file_pendukung')->store('kunjungan-kerja', 'public');
            }

            $updateData = [
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'tanggal_kunjungan' => $dates['start'],
                'tanggal_selesai' => $dates['end'],
                'waktu' => $validated['waktu_mulai'] ?? null,
                'id_jenis_kunjungan' => $validated['id_jenis_kunjungan'],
                'tipe_tujuan' => $validated['tipe_tujuan'],
                'id_provinsi' => $validated['tipe_tujuan'] == 'dalam_negeri' ? $validated['id_provinsi'] : null,
                'tujuan_luar_negeri' => $validated['tipe_tujuan'] == 'luar_negeri' ? ($validated['tujuan_luar_negeri'] ?? null) : null,
                'id_anggota' => $validated['anggota_dewan_id'] ?? [],
                'rombongan' => $validated['rombongan'] ?? [],
                'file_path' => $filePath,
            ];

            // Only update petugas if user is super admin (since field is hidden for others)
            if (auth()->user()->isSuperAdmin()) {
                $updateData['id_petugas'] = $validated['petugas_id'] ?? [];
            }

            $kunjungan->update($updateData);

            DB::commit();
            return redirect()->route('kunjungan-kerja')->with('success', 'Kegiatan kunjungan kerja berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function parseDateRange(string $dateRange): array
    {
        $dates = preg_split('/(\s+to\s+|\s+-\s+)/', $dateRange);
        $start = trim($dates[0] ?? '');
        $end = trim($dates[1] ?? $start);

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * Hapus kegiatan dari database.
     */
    public function destroy($id)
    {
        $kunjungan = KunjunganKerja::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($kunjungan->file_path) {
                Storage::disk('public')->delete($kunjungan->file_path);
            }

            $kunjungan->delete();

            DB::commit();
            return redirect()->route('kunjungan-kerja')->with('success', 'Kegiatan kunjungan kerja berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
