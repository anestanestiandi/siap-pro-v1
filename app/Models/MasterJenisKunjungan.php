<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKunjungan extends Model
{
    protected $table = 'master_jenis_kunjungan';
    protected $primaryKey = 'id_jenis_kunjungan';

    protected $fillable = ['nama_jenis', 'tipe', 'deskripsi', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kunjunganKerja()
    {
        return $this->hasMany(KunjunganKerja::class, 'id_jenis_kunjungan', 'id_jenis_kunjungan');
    }
}
