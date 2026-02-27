<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPegawai extends Model
{
    protected $table = 'master_pegawai';

    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'unit_kerja',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
