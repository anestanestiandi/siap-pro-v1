<?php

namespace App\Observers;

use App\Models\KunjunganKerja;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class KunjunganKerjaObserver
{
    /**
     * Handle the KunjunganKerja "created" event.
     */
    public function created(KunjunganKerja $kunjunganKerja): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => KunjunganKerja::class,
                'model_id'    => $kunjunganKerja->id_kunjungan,
                'user_id'     => Auth::id(),
                'action'      => 'create',
                'description' => 'Kegiatan baru ditambahkan',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }

    /**
     * Handle the KunjunganKerja "updated" event.
     */
    public function updated(KunjunganKerja $kunjunganKerja): void
    {
        if (Auth::check()) {
            $changes = $kunjunganKerja->getChanges();

            // Remove updated_at from changes if it's the only change
            unset($changes['updated_at']);

            if (!empty($changes)) {
                HistoryLog::create([
                    'model_type'  => KunjunganKerja::class,
                    'model_id'    => $kunjunganKerja->id_kunjungan,
                    'user_id'     => Auth::id(),
                    'action'      => 'update',
                    'description' => 'Detail kegiatan diperbarui',
                    'changes'     => $changes,
                    'status'      => 'success',
                ]);
            }
        }
    }

    /**
     * Handle the KunjunganKerja "deleted" event.
     */
    public function deleted(KunjunganKerja $kunjunganKerja): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => KunjunganKerja::class,
                'model_id'    => $kunjunganKerja->id_kunjungan,
                'user_id'     => Auth::id(),
                'action'      => 'delete',
                'description' => 'Kegiatan dihapus',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }
}
