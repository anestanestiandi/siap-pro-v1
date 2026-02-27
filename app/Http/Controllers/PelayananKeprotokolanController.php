<?php

namespace App\Http\Controllers;

use App\Models\PelayananKeprotokolan;
use App\Models\MasterAnggotaDewan;
use App\Models\MasterJenisPelayanan;
use App\Models\MasterPetugasProtokol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PelayananKeprotokolanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan pelayanan keprotokolan.
     */
    public function index(Request $request)
    {
        // 1. Initial Query
        $query = PelayananKeprotokolan::with(['anggotaDewan', 'jenisPelayanan', 'petugas']);

        // 2. Define Filter Logic (Closure for reusability)
        $applyFilter = function ($q) use ($request) {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $q->whereBetween('tanggal_kegiatan', [$request->start_date, $request->end_date]);
            } elseif ($request->filled('month')) {
                try {
                    $date = \Carbon\Carbon::parse($request->month);
                    $q->whereYear('tanggal_kegiatan', $date->year)
                        ->whereMonth('tanggal_kegiatan', $date->month);
                } catch (\Exception $e) {
                    // Fallback or ignore invalid date
                }
            } elseif ($request->filled('year')) {
                $q->whereYear('tanggal_kegiatan', $request->year);
            }

            // Search Logic
            if ($request->filled('search')) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('tempat', 'like', "%{$search}%")
                        ->orWhereHas('anggotaDewan', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('jenisPelayanan', function ($q) use ($search) {
                            $q->where('nama_jenis', 'like', "%{$search}%");
                        });
                });
            }
        };

        // Apply Filter to Main Query
        $applyFilter($query);

        // 3. Summary Cards Data (Filtered)
        $totalKegiatan = $query->count();

        $jenisPelayananSummary = MasterJenisPelayanan::withCount([
            'pelayananKeprotokolan as total' => function ($q) use ($applyFilter) {
                $applyFilter($q);
            }
        ])->get()
        ->map(function ($jenis) {
            $colors = [
                ['bg' => 'bg-gradient-to-br from-blue-50 to-blue-100', 'border' => 'border-blue-100', 'icon_bg' => 'from-blue-500 to-blue-600', 'shadow' => 'shadow-blue-500/30', 'text' => 'text-blue-600', 'chip_bg' => 'bg-blue-100'],
                ['bg' => 'bg-gradient-to-br from-red-50 to-red-100', 'border' => 'border-red-100', 'icon_bg' => 'from-red-500 to-red-600', 'shadow' => 'shadow-red-500/30', 'text' => 'text-red-600', 'chip_bg' => 'bg-red-100'],
                ['bg' => 'bg-gradient-to-br from-green-50 to-green-100', 'border' => 'border-green-100', 'icon_bg' => 'from-green-500 to-green-600', 'shadow' => 'shadow-green-500/30', 'text' => 'text-green-600', 'chip_bg' => 'bg-green-100'],
                ['bg' => 'bg-gradient-to-br from-purple-50 to-purple-100', 'border' => 'border-purple-100', 'icon_bg' => 'from-purple-500 to-purple-600', 'shadow' => 'shadow-purple-500/30', 'text' => 'text-purple-600', 'chip_bg' => 'bg-purple-100'],
                ['bg' => 'bg-gradient-to-br from-amber-50 to-amber-100', 'border' => 'border-amber-100', 'icon_bg' => 'from-amber-500 to-amber-600', 'shadow' => 'shadow-amber-500/30', 'text' => 'text-amber-600', 'chip_bg' => 'bg-amber-100'],
                ['bg' => 'bg-gradient-to-br from-indigo-50 to-indigo-100', 'border' => 'border-indigo-100', 'icon_bg' => 'from-indigo-500 to-indigo-600', 'shadow' => 'shadow-indigo-500/30', 'text' => 'text-indigo-600', 'chip_bg' => 'bg-indigo-100'],
                ['bg' => 'bg-gradient-to-br from-teal-50 to-teal-100', 'border' => 'border-teal-100', 'icon_bg' => 'from-teal-500 to-teal-600', 'shadow' => 'shadow-teal-500/30', 'text' => 'text-teal-600', 'chip_bg' => 'bg-teal-100'],
                ['bg' => 'bg-gradient-to-br from-pink-50 to-pink-100', 'border' => 'border-pink-100', 'icon_bg' => 'from-pink-500 to-pink-600', 'shadow' => 'shadow-pink-500/30', 'text' => 'text-pink-600', 'chip_bg' => 'bg-pink-100'],
            ];
            
            $colorIndex = ($jenis->id_jenis_pelayanan - 1) % count($colors);
            $jenis->style = $colors[$colorIndex];
            
            $jenis->label = match ($jenis->nama_jenis) {
                'Sidang Kabinet' => 'Sidang',
                'Pelayanan Keprotokolan Bandara' => 'Bandara',
                'Pelayanan Keprotokolan Acara Dalam Kota' => 'Lokal',
                'Audiensi Dengan Pimpinan DPRD' => 'Audiensi',
                'Audiensi Kepada RI 1' => 'Aud. RI 1',
                'Audiensi Kepada RI 2' => 'Aud. RI 2',
                'Penerimaan Kunjungan Kerja' => 'Kunjungan',
                default => 'Umum',
            };

            return $jenis;
        });

        $totalProtokol = MasterPetugasProtokol::count();

        // 4. Sorting
        $query->orderBy('created_at', 'desc');

        // 5. Export Logic
        if ($request->has('export')) {
            $kegiatan = $query->get();
            $columns = $request->input('columns', []);
            
            if (empty($columns)) {
                $columns = ['tanggal', 'waktu', 'nama_kegiatan', 'jenis_pelayanan', 'anggota_dewan', 'tempat'];
            }

            if ($request->export == 'excel') {
                $fileName = 'laporan-keprotokolan-' . now()->format('Y-m-d') . '.xls';
                return response(view('pelayanan-keprotokolan.excel', compact('kegiatan', 'columns')))
                    ->header('Content-Type', 'application/vnd.ms-excel')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            } 
            
            if ($request->export == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelayanan-keprotokolan.pdf', compact('kegiatan', 'columns'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('laporan-keprotokolan.pdf');
            }
        }

        // 6. Pagination
        $kegiatan = $query->paginate(10)->withQueryString();

        return view('pelayanan-keprotokolan.index', compact(
            'kegiatan',
            'totalKegiatan',
            'totalProtokol',
            'jenisPelayananSummary'
        ));
    }


    /**
     * Tampilkan form tambah kegiatan baru.
     */
    public function create()
    {
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisPelayanan = MasterJenisPelayanan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();

        return view('pelayanan-keprotokolan.create', compact(
            'anggotaDewan',
            'jenisPelayanan',
            'petugasProtokol'
        ));
    }

    /**
     * Simpan kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'anggota_dewan_id' => 'required|array',
            'anggota_dewan_id.*' => 'exists:master_anggota_dewan,id_anggota',
            'tanggal_kegiatan' => 'required|date',
            'waktu' => 'required',
            'id_jenis_pelayanan' => 'required|integer|exists:master_jenis_pelayanan,id_jenis_pelayanan',
            'tempat' => 'nullable|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];

        $validated = $request->validate($rules);

        // Upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('pelayanan-keprotokolan', 'public');
        }

        // Insert data pelayanan keprotokolan
        $pelayanan = PelayananKeprotokolan::create([
            'id_anggota' => $validated['anggota_dewan_id'][0] ?? null,
            'id_jenis_pelayanan' => $validated['id_jenis_pelayanan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'waktu' => $validated['waktu'],
            'tempat' => $validated['tempat'] ?? null,
            'id_petugas' => $validated['petugas_id'][0] ?? null, // Sync first petugas to column
            'file_path' => $filePath,
            'created_by' => Auth::user()->id_user ?? Auth::id(),
        ]);

        // Attach anggota dewan to pivot table
        if (!empty($validated['anggota_dewan_id'])) {
            $pelayanan->anggotaDewan()->sync($validated['anggota_dewan_id']);
        }

        // Attach petugas to pivot table
        if (!empty($validated['petugas_id'])) {
            $pelayanan->petugas()->sync($validated['petugas_id']);
        }

        return redirect()->route('pelayanan-keprotokolan')
            ->with('success', 'Kegiatan pelayanan keprotokolan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kegiatan.
     */
    public function show($id)
    {
        $item = PelayananKeprotokolan::with(['anggotaDewan', 'jenisPelayanan', 'petugas', 'creator', 'updater'])
            ->findOrFail($id);

        return view('pelayanan-keprotokolan.show', compact('item'));
    }

    /**
     * Tampilkan form edit kegiatan.
     */
    public function edit($id)
    {
        $item = PelayananKeprotokolan::findOrFail($id);
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisPelayanan = MasterJenisPelayanan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();

        return view('pelayanan-keprotokolan.edit', compact('item', 'anggotaDewan', 'jenisPelayanan', 'petugasProtokol'));
    }

    /**
     * Update data kegiatan di database.
     */
    public function update(Request $request, $id)
    {
        $pelayanan = PelayananKeprotokolan::findOrFail($id);

        $rules = [
            'anggota_dewan_id' => 'required|array',
            'anggota_dewan_id.*' => 'exists:master_anggota_dewan,id_anggota',
            'tanggal_kegiatan' => 'required|date',
            'waktu' => 'required',
            'id_jenis_pelayanan' => 'required|integer|exists:master_jenis_pelayanan,id_jenis_pelayanan',
            'tempat' => 'nullable|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];

        $validated = $request->validate($rules);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($pelayanan->file_path) {
                Storage::disk('public')->delete($pelayanan->file_path);
            }
            $pelayanan->file_path = $request->file('file')->store('pelayanan-keprotokolan', 'public');
        }

        $pelayanan->update([
            'id_anggota' => $validated['anggota_dewan_id'][0] ?? null,
            'id_jenis_pelayanan' => $validated['id_jenis_pelayanan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'waktu' => $validated['waktu'],
            'tempat' => $validated['tempat'] ?? null,
            'id_petugas' => $validated['petugas_id'][0] ?? null, // Sync first petugas to column
            'updated_by' => Auth::id(),
        ]);

        if (isset($validated['anggota_dewan_id'])) {
            $pelayanan->anggotaDewan()->sync($validated['anggota_dewan_id']);
        }

        if (isset($validated['petugas_id'])) {
            $pelayanan->petugas()->sync($validated['petugas_id']);
        }

        return redirect()->route('pelayanan-keprotokolan')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan.
     */
    public function destroy($id)
    {
        $pelayanan = PelayananKeprotokolan::findOrFail($id);

        // Delete file if exists
        if ($pelayanan->file_path) {
            Storage::disk('public')->delete($pelayanan->file_path);
        }

        $pelayanan->delete();

        return redirect()->route('pelayanan-keprotokolan')
            ->with('success', 'Kegiatan pelayanan keprotokolan berhasil dihapus.');
    }
}
