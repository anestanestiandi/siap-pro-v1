<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProvinsi extends Model
{
    protected $table = 'master_provinsi';
    protected $primaryKey = 'id_provinsi';

    protected $fillable = ['nama_provinsi', 'kode_provinsi', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kunjunganKerja()
    {
        return $this->hasMany(KunjunganKerja::class, 'id_provinsi', 'id_provinsi');
    }
}
