<?php

namespace App\Observers;

use App\Models\AdministrasiPerjalananDinas;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class AdmPerjalananDinasObserver
{
    /**
     * Handle the AdministrasiPerjalananDinas "created" event.
     */
    public function created(AdministrasiPerjalananDinas $adm): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => AdministrasiPerjalananDinas::class,
                'model_id'    => $adm->id_adm_perjalanan_dinas,
                'user_id'     => Auth::id(),
                'action'      => 'create',
                'description' => 'Kegiatan baru ditambahkan',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }

    /**
     * Handle the AdministrasiPerjalananDinas "updated" event.
     */
    public function updated(AdministrasiPerjalananDinas $adm): void
    {
        if (Auth::check()) {
            $changes = $adm->getChanges();

            // Remove updated_at from changes if it's the only change
            unset($changes['updated_at']);

            if (!empty($changes)) {
                HistoryLog::create([
                    'model_type'  => AdministrasiPerjalananDinas::class,
                    'model_id'    => $adm->id_adm_perjalanan_dinas,
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
     * Handle the AdministrasiPerjalananDinas "deleted" event.
     */
    public function deleted(AdministrasiPerjalananDinas $adm): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => AdministrasiPerjalananDinas::class,
                'model_id'    => $adm->id_adm_perjalanan_dinas,
                'user_id'     => Auth::id(),
                'action'      => 'delete',
                'description' => 'Kegiatan dihapus',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }
}
