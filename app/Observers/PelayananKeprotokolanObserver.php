<?php

namespace App\Observers;

use App\Models\PelayananKeprotokolan;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class PelayananKeprotokolanObserver
{
    /**
     * Handle the PelayananKeprotokolan "created" event.
     */
    public function created(PelayananKeprotokolan $pelayananKeprotokolan): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => PelayananKeprotokolan::class,
                'model_id'    => $pelayananKeprotokolan->id_pelayanan,
                'user_id'     => Auth::id(),
                'action'      => 'create',
                'description' => 'Kegiatan baru ditambahkan',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }

    /**
     * Handle the PelayananKeprotokolan "updated" event.
     */
    public function updated(PelayananKeprotokolan $pelayananKeprotokolan): void
    {
        if (Auth::check()) {
            $changes = $pelayananKeprotokolan->getChanges();

            // Remove updated_at from changes if it's the only change
            unset($changes['updated_at']);

            if (!empty($changes)) {
                HistoryLog::create([
                    'model_type'  => PelayananKeprotokolan::class,
                    'model_id'    => $pelayananKeprotokolan->id_pelayanan,
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
     * Handle the PelayananKeprotokolan "deleted" event.
     */
    public function deleted(PelayananKeprotokolan $pelayananKeprotokolan): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => PelayananKeprotokolan::class,
                'model_id'    => $pelayananKeprotokolan->id_pelayanan,
                'user_id'     => Auth::id(),
                'action'      => 'delete',
                'description' => 'Kegiatan dihapus',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }
}
