<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPerjalananDinas extends Model
{
    protected $table = 'master_jenis_perjalanan_dinas';
    protected $primaryKey = 'id_jenis_perjalanan';

    protected $fillable = ['nama_jenis', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke tabel administrasi perjalanan dinas.
     */
    public function admPerjalananDinas()
    {
        return $this->hasMany(AdministrasiPerjalananDinas::class, 'id_jenis_perjalanan_dinas', 'id_jenis_perjalanan');
    }
}
