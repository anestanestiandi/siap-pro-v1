<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPetugasProtokol extends Model
{
    protected $table = 'master_petugas_protokol';
    protected $primaryKey = 'id_petugas';

    protected $fillable = ['nama', 'user_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Pivot relationships removed as per request to use module-specific JSON columns.
}
