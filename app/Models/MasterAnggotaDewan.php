<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAnggotaDewan extends Model
{
    protected $table = 'master_anggota_dewan';
    protected $primaryKey = 'id_anggota';

    protected $fillable = ['nama', 'user_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Pivot relationships removed as per request to use module-specific JSON columns.
}
