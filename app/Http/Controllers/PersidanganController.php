<?php

namespace App\Http\Controllers;

use App\Models\Persidangan;
use App\Models\MasterAnggotaDewan;
use App\Models\MasterJenisPersidangan;
use App\Models\MasterPetugasProtokol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersidanganController extends Controller
{
    /**
     * Tampilkan daftar kegiatan persidangan.
     */
    public function index(Request $request)
    {
        // 1. Initial Query
        $query = Persidangan::with(['jenisPersidangan']);

        // 2. Define Filter Logic (Closure for reusability)
        $applyFilter = function ($q) use ($request) {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $q->whereBetween('tanggal_persidangan', [$request->start_date, $request->end_date]);
            } elseif ($request->filled('month')) {
                try {
                    $date = \Carbon\Carbon::parse($request->month);
                    $q->whereYear('tanggal_persidangan', $date->year)
                        ->whereMonth('tanggal_persidangan', $date->month);
                } catch (\Exception $e) {
                    // Fallback or ignore invalid date
                }
            } elseif ($request->filled('year')) {
                $q->whereYear('tanggal_persidangan', $request->year);
            }

            // Search Logic
            if ($request->filled('search')) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_persidangan', 'like', "%{$search}%")
                        ->orWhere('tempat', 'like', "%{$search}%")
                        ->orWhereHas('jenisPersidangan', function ($q) use ($search) {
                            $q->where('nama_jenis', 'like', "%{$search}%");
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
                });
            }
        };

        // Apply Filter to Main Query
        $applyFilter($query);

        // 3. Summary Cards Data (Filtered)
        // Count filtered items for "Total Kegiatan" card
        $totalKegiatan = $query->count();

        // Count filtered items per "Jenis Persidangan" card
        $jenisPersidanganSummary = MasterJenisPersidangan::withCount([
            'persidangan as total' => function ($q) use ($applyFilter) {
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
            ];
            
            $colorIndex = ($jenis->id_jenis_persidangan - 1) % count($colors);
            $jenis->style = $colors[$colorIndex];
            
            $jenis->label = match ($jenis->nama_jenis) {
                'Rapat Paripurna' => 'Paripurna',
                'Rapat Pimpinan' => 'Pimpinan',
                'Rapat Badan Musyawarah' => 'Bamus',
                'Rapat Badan Anggaran' => 'Banggar',
                'Diskusi Terbatas' => 'Diskusi',
                'Pertemuan Terbatas' => 'Pertemuan',
                'Sidang Pleno' => 'Pleno',
                default => (str_starts_with($jenis->nama_jenis, 'Rapat ') 
                    ? str_replace('Rapat ', '', $jenis->nama_jenis) 
                    : $jenis->nama_jenis),
            };

            return $jenis;
        });

        $totalProtokol = MasterPetugasProtokol::count();

        // 4. Sorting
        $query->orderBy('tanggal_persidangan', 'desc')->orderBy('waktu', 'desc');

        // 5. Export PDF (Optional, derived from existing controller logic)
        // 5. Export Logic
        if ($request->has('export')) {
            $kegiatan = $query->get();
            $columns = $request->input('columns', []);
            
            // Ensure minimum columns if none selected (fallback)
            if (empty($columns)) {
                $columns = ['tanggal', 'waktu', 'nama_persidangan', 'jenis_persidangan', 'anggota_dewan', 'tempat'];
            }

            if ($request->export == 'excel') {
                $fileName = 'laporan-persidangan-' . now()->format('Y-m-d') . '.xls';
                
                return response(view('persidangan.excel', compact('kegiatan', 'columns')))
                    ->header('Content-Type', 'application/vnd.ms-excel')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            } 
            
            if ($request->export == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('persidangan.pdf', compact('kegiatan', 'columns'));
                $pdf->setPaper('a4', 'landscape'); // Landscape for better column fit
                return $pdf->download('laporan-persidangan.pdf');
            }
        }

        // 6. Pagination
        $kegiatan = $query->paginate(10)->onEachSide(1)->withQueryString();

        return view('persidangan.index', compact(
            'kegiatan',
            'totalKegiatan',
            'jenisPersidanganSummary',
            'totalProtokol'
        ));
    }


    /**
     * Tampilkan form tambah kegiatan baru.
     */
    public function create()
    {
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisPersidangan = MasterJenisPersidangan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();

        return view('persidangan.create', compact(
            'anggotaDewan',
            'jenisPersidangan',
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
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'tanggal_persidangan' => 'required|date',
            'waktu' => 'required',
            'id_jenis_persidangan' => 'required|integer|exists:master_jenis_persidangan,id_jenis_persidangan',
            'tempat' => 'nullable|string|max:255',
            'nama_persidangan' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ];

        $validated = $request->validate($rules);

        // Upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('persidangan', 'public');
        }

        $persidangan = Persidangan::create([
            'id_anggota' => $validated['anggota_dewan_id'], // Simpan sebagai array JSON
            'id_petugas' => $validated['petugas_id'] ?? [], // Simpan sebagai array JSON
            'id_jenis_persidangan' => $validated['id_jenis_persidangan'],
            'nama_persidangan' => $validated['nama_persidangan'],
            'tanggal_persidangan' => $validated['tanggal_persidangan'],
            'waktu' => $validated['waktu'],
            'tempat' => $validated['tempat'] ?? null,
            'file_path' => $filePath,
            'created_by' => Auth::user()->id_user ?? Auth::id(), // Handle if id_user property usage varies
        ]);

        return redirect()->route('persidangan')
            ->with('success', 'Kegiatan persidangan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kegiatan.
     */
    public function show($id)
    {
        $item = Persidangan::with(['jenisPersidangan', 'creator', 'updater', 'historyLogs', 'historyLogs.user'])
            ->findOrFail($id);

        return view('persidangan.show', compact('item'));
    }

    /**
     * Tampilkan form edit kegiatan.
     */
    public function edit($id)
    {
        $item = Persidangan::findOrFail($id);
        $anggotaDewan = MasterAnggotaDewan::where('is_active', 1)->orderBy('nama')->get();
        $jenisPersidangan = MasterJenisPersidangan::where('is_active', 1)->orderBy('nama_jenis')->get();
        $petugasProtokol = MasterPetugasProtokol::where('is_active', 1)->orderBy('nama')->get();

        return view('persidangan.edit', compact('item', 'anggotaDewan', 'jenisPersidangan', 'petugasProtokol'));
    }

    /**
     * Update data kegiatan di database.
     */
    public function update(Request $request, $id)
    {
        $persidangan = Persidangan::findOrFail($id);

        $rules = [
            'anggota_dewan_id' => 'required|array',
            'anggota_dewan_id.*' => 'exists:master_anggota_dewan,id_anggota',
            'petugas_id' => 'nullable|array',
            'petugas_id.*' => 'exists:master_petugas_protokol,id_petugas',
            'tanggal_persidangan' => 'required|date',
            'waktu' => 'required',
            'id_jenis_persidangan' => 'required|integer|exists:master_jenis_persidangan,id_jenis_persidangan',
            'tempat' => 'nullable|string|max:255',
            'nama_persidangan' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,jpg,png,xls,xlsx|max:10240',
        ];

        $validated = $request->validate($rules);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($persidangan->file_path) {
                Storage::disk('public')->delete($persidangan->file_path);
            }
            $persidangan->file_path = $request->file('file')->store('persidangan', 'public');
        }

        $updateData = [
            'id_anggota' => $validated['anggota_dewan_id'], // Update sebagai array JSON
            'id_jenis_persidangan' => $validated['id_jenis_persidangan'],
            'nama_persidangan' => $validated['nama_persidangan'],
            'tanggal_persidangan' => $validated['tanggal_persidangan'],
            'waktu' => $validated['waktu'],
            'tempat' => $validated['tempat'] ?? null,
            'updated_by' => Auth::id(),
        ];

        // Only update petugas if user is super admin (since field is hidden for others)
        if (auth()->user()->isSuperAdmin()) {
            $updateData['id_petugas'] = $validated['petugas_id'] ?? [];
        }

        $persidangan->update($updateData);

        return redirect()->route('persidangan')
            ->with('success', 'Kegiatan persidangan berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan.
     */
    public function destroy($id)
    {
        $persidangan = Persidangan::findOrFail($id);

        // Delete file if exists
        if ($persidangan->file_path) {
            Storage::disk('public')->delete($persidangan->file_path);
        }

        $persidangan->delete();

        return redirect()->route('persidangan')
            ->with('success', 'Kegiatan persidangan berhasil dihapus.');
    }

}
