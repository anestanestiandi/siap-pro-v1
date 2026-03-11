<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPegawai;


class AdmPerjalananDinasController extends Controller
{
    /**
     * Tampilkan daftar kegiatan administrasi perjalanan dinas.
     */
    public function index(Request $request)
    {
        $query = \App\Models\AdministrasiPerjalananDinas::query();
        
        // Eager load relationships
        $query->with(['jenisPerjalananDinas', 'creator', 'updater']);

        // Define Filter Logic
        $applyFilter = function ($q) use ($request) {
            // Date Filters
            if ($request->filled('start_date') && $request->filled('end_date')) {
                // For travel, we check if the requested range overlaps with the activity range OR 
                // just filter by start_date for simplicity matching other modules
                $q->whereBetween('tanggal_mulai', [$request->start_date, $request->end_date]);
            } elseif ($request->filled('month')) {
                try {
                    $date = \Carbon\Carbon::parse($request->month);
                    $q->whereYear('tanggal_mulai', $date->year)
                      ->whereMonth('tanggal_mulai', $date->month);
                } catch (\Exception $e) {
                    // Ignore invalid date
                }
            } elseif ($request->filled('year')) {
                $q->whereYear('tanggal_mulai', $request->year);
            }

            // Search Logic
            if ($request->filled('search')) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('tujuan', 'like', "%{$search}%")
                        ->orWhere('pelaksana', 'like', "%{$search}%")
                        ->orWhereHas('jenisPerjalananDinas', function ($q) use ($search) {
                            $q->where('nama_jenis', 'like', "%{$search}%");
                        })
                        ->orWhereHas('petugas', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%");
                        });
                });
            }
        };

        // Apply Filter to Main Query
        $applyFilter($query);

        // Summary Cards Data
        $totalKegiatan = $query->count();

        // Count per Jenis Perjalanan
        $jenisPerjalananSummary = \App\Models\MasterJenisPerjalananDinas::withCount([
            'admPerjalananDinas as total' => function ($q) use ($applyFilter) {
                $applyFilter($q);
            }
        ])->get()
        ->map(function ($jenis) {
            $colors = [
                ['bg' => 'bg-gradient-to-br from-green-50 to-green-100', 'border' => 'border-green-100', 'icon_bg' => 'from-green-500 to-green-600', 'shadow' => 'shadow-green-500/30', 'text' => 'text-green-600', 'chip_bg' => 'bg-green-100'],
                ['bg' => 'bg-gradient-to-br from-amber-50 to-amber-100', 'border' => 'border-amber-100', 'icon_bg' => 'from-amber-500 to-amber-600', 'shadow' => 'shadow-amber-500/30', 'text' => 'text-amber-600', 'chip_bg' => 'bg-amber-100'],
                ['bg' => 'bg-gradient-to-br from-rose-50 to-rose-100', 'border' => 'border-rose-100', 'icon_bg' => 'from-rose-500 to-rose-600', 'shadow' => 'shadow-rose-500/30', 'text' => 'text-rose-600', 'chip_bg' => 'bg-rose-100'],
                ['bg' => 'bg-gradient-to-br from-teal-50 to-teal-100', 'border' => 'border-teal-100', 'icon_bg' => 'from-teal-500 to-teal-600', 'shadow' => 'shadow-teal-500/30', 'text' => 'text-teal-600', 'chip_bg' => 'bg-teal-100'],
            ];
            
            // Assign color based on ID (safe modulo)
            $colorIndex = ($jenis->id_jenis_perjalanan - 1) % count($colors);
            $jenis->style = $colors[$colorIndex];
            
            // Simplified label
            $jenis->label = str_replace('Administrasi Perjalanan Dinas ', '', $jenis->nama_jenis);
            $jenis->nama_jenis = $jenis->nama_jenis; // Alias for view consistency

            return $jenis;
        });

        $totalProtokol = \App\Models\MasterPetugasProtokol::count();

        // Sorting
        $query->orderBy('tanggal_mulai', 'desc')->orderBy('waktu', 'desc');

        // Export Logic
        if ($request->has('export')) {
            $kegiatan = $query->get();
            $columns = $request->input('columns', []);
            
            // Ensure minimum columns if none selected (fallback)
            if (empty($columns)) {
                $columns = ['tanggal', 'waktu', 'nama_kegiatan', 'jenis_perjalanan', 'pelaksana', 'tujuan'];
            }

            if ($request->export == 'excel') {
                $fileName = 'laporan-perjalanan-dinas-' . now()->format('Y-m-d') . '.xls';
                
                return response(view('administrasi-perjalanan-dinas.excel', compact('kegiatan', 'columns')))
                    ->header('Content-Type', 'application/vnd.ms-excel')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            } 
            
            if ($request->export == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('administrasi-perjalanan-dinas.pdf', compact('kegiatan', 'columns'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('laporan-perjalanan-dinas.pdf');
            }
        }
        // If export requested...

        $kegiatan = $query->paginate(10)->onEachSide(1)->withQueryString();

        return view('administrasi-perjalanan-dinas.index', compact('kegiatan', 'totalKegiatan', 'jenisPerjalananSummary', 'totalProtokol'));
    }

    /**
     * Tampilkan form tambah kegiatan baru.
     */
    public function create()
    {
        // Dropdown data
        $jenisPerjalanan = \Illuminate\Support\Facades\DB::table('master_jenis_perjalanan_dinas')->get();
        // Determine ID for Luar Negeri
        $luarNegeriId = $jenisPerjalanan->filter(fn($j) => str_contains(strtolower($j->nama_jenis), 'luar negeri'))->pluck('id_jenis_perjalanan')->first();

        // Fetch Provinces for 'Dalam Negeri' option (if any)
        // If master_provinsi is not a model, use DB
        $provinsi = \Illuminate\Support\Facades\DB::table('master_provinsi')->orderBy('nama_provinsi')->get();

        $petugasProtokol = \App\Models\MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();
        $masterPegawai = MasterPegawai::where('is_active', 1)->orderBy('nama_lengkap')->get();

        return view('administrasi-perjalanan-dinas.create', compact('jenisPerjalanan', 'petugasProtokol', 'provinsi', 'luarNegeriId', 'masterPegawai'));
    }


    /**
     * Simpan kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        // MERGE Tujuan logic
        $tujuan = $request->filled('tujuan_manual') ? $request->tujuan_manual : $request->tujuan_provinsi;
        $request->merge(['tujuan' => $tujuan]);

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|string', // Range format date
            'id_jenis_perjalanan_dinas' => 'required|exists:master_jenis_perjalanan_dinas,id_jenis_perjalanan',
            'pelaksana' => 'required|array',
            'pelaksana.*' => 'string',
            'tujuan' => 'required|string',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ]);


        // Parse Date Range "YYYY-MM-DD to YYYY-MM-DD"
        $dates = preg_split('/(\s+to\s+|\s+-\s+)/', $request->tanggal);
        $tanggalMulai = trim($dates[0]);
        $tanggalSelesai = isset($dates[1]) ? trim($dates[1]) : $tanggalMulai;

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('administrasi_perjalanan_dinas', 'public');
        }

        $adm = \App\Models\AdministrasiPerjalananDinas::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'id_jenis_perjalanan_dinas' => $request->id_jenis_perjalanan_dinas,
            'id_petugas' => $request->petugas_id ?? [],
            'tujuan' => $request->tujuan,
            'pelaksana' => implode('; ', $request->pelaksana),
            'waktu' => '09:00', // Default time

            'file_path' => $filePath,
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
            'updated_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('administrasi-perjalanan-dinas')
            ->with('success', 'Kegiatan perjalanan dinas berhasil ditambahkan.');
    }
    /**
     * Tampilkan detail kegiatan perjalanan dinas.
     */
    public function show($id)
    {
        $item = \App\Models\AdministrasiPerjalananDinas::with(['jenisPerjalananDinas', 'creator', 'updater', 'historyLogs', 'historyLogs.user'])->findOrFail($id);
        
        return view('administrasi-perjalanan-dinas.show', compact('item'));
    }

    /**
     * Tampilkan form untuk mengedit kegiatan.
     */
    public function edit($id)
    {
        $item = \App\Models\AdministrasiPerjalananDinas::findOrFail($id);
        
        // Eager load related data for dropdowns
        $jenisPerjalanan = \Illuminate\Support\Facades\DB::table('master_jenis_perjalanan_dinas')->get();
        $luarNegeriId = $jenisPerjalanan->filter(fn($j) => str_contains(strtolower($j->nama_jenis), 'luar negeri'))->pluck('id_jenis_perjalanan')->first();
        $provinsi = \Illuminate\Support\Facades\DB::table('master_provinsi')->orderBy('nama_provinsi')->get();
        $petugasProtokol = \App\Models\MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();
        $masterPegawai = MasterPegawai::where('is_active', 1)->orderBy('nama_lengkap')->get();

        return view('administrasi-perjalanan-dinas.edit', compact('item', 'jenisPerjalanan', 'petugasProtokol', 'provinsi', 'luarNegeriId', 'masterPegawai'));
    }


    /**
     * Perbarui data kegiatan di database.
     */
    public function update(Request $request, $id)
    {
        $item = \App\Models\AdministrasiPerjalananDinas::findOrFail($id);

        // MERGE Tujuan logic
        $tujuan = $request->filled('tujuan_manual') ? $request->tujuan_manual : $request->tujuan_provinsi;
        $request->merge(['tujuan' => $tujuan]);

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|string', // Range format date
            'id_jenis_perjalanan_dinas' => 'required|exists:master_jenis_perjalanan_dinas,id_jenis_perjalanan',
            'pelaksana' => 'required|array',
            'pelaksana.*' => 'string',
            'tujuan' => 'required|string',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ]);


        // Parse Date Range
        $dates = preg_split('/(\s+to\s+|\s+-\s+)/', $request->tanggal);
        $tanggalMulai = trim($dates[0]);
        $tanggalSelesai = isset($dates[1]) ? trim($dates[1]) : $tanggalMulai;

        $data = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'id_jenis_perjalanan_dinas' => $request->id_jenis_perjalanan_dinas,
            'tujuan' => $request->tujuan,
            'pelaksana' => implode('; ', $request->pelaksana),
            'updated_by' => \Illuminate\Support\Facades\Auth::id(),
        ];

        // Only update petugas if user is super admin (since field is hidden for others)
        if (auth()->user()->isSuperAdmin()) {
            $data['id_petugas'] = $request->petugas_id ?? [];
        }

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($item->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->file_path);
            }
            $data['file_path'] = $request->file('file')->store('administrasi_perjalanan_dinas', 'public');
        }

        $item->update($data);

        return redirect()->route('administrasi-perjalanan-dinas')
            ->with('success', 'Kegiatan perjalanan dinas berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan dari database.
     */
    public function destroy($id)
    {
        $item = \App\Models\AdministrasiPerjalananDinas::findOrFail($id);
        
        // Delete file if exists
        if ($item->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->file_path);
        }

        $item->delete();

        return redirect()->route('administrasi-perjalanan-dinas')
            ->with('success', 'Kegiatan perjalanan dinas berhasil dihapus.');
    }
}
