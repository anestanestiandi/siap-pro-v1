<?php

namespace App\Observers;

use App\Models\Persidangan;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class PersidanganObserver
{
    /**
     * Handle the Persidangan "created" event.
     */
    public function created(Persidangan $persidangan): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type' => Persidangan::class,
                'model_id'   => $persidangan->id_persidangan,
                'user_id'    => Auth::id(),
                'action'     => 'create',
                'description' => 'Kegiatan baru ditambahkan',
                'changes'    => null,
                'status'     => 'success',
            ]);
        }
    }

    /**
     * Handle the Persidangan "updated" event.
     */
    public function updated(Persidangan $persidangan): void
    {
        if (Auth::check()) {
            $changes = $persidangan->getChanges();

            // Remove updated_at from changes if it's the only change
            unset($changes['updated_at']);

            if (!empty($changes)) {
                HistoryLog::create([
                    'model_type'  => Persidangan::class,
                    'model_id'    => $persidangan->id_persidangan,
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
     * Handle the Persidangan "deleted" event.
     */
    public function deleted(Persidangan $persidangan): void
    {
        if (Auth::check()) {
            HistoryLog::create([
                'model_type'  => Persidangan::class,
                'model_id'    => $persidangan->id_persidangan,
                'user_id'     => Auth::id(),
                'action'      => 'delete',
                'description' => 'Kegiatan dihapus',
                'changes'     => null,
                'status'      => 'success',
            ]);
        }
    }
}
