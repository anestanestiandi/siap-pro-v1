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

    public function persidangan()
    {
        return $this->belongsToMany(Persidangan::class, 'persidangan_anggota_dewan', 'id_anggota', 'id_persidangan');
    }

    public function pelayananKeprotokolan()
    {
        return $this->belongsToMany(PelayananKeprotokolan::class, 'pelayanan_anggota_dewan', 'id_anggota', 'id_pelayanan');
    }

    public function kunjunganKerja()
    {
        return $this->belongsToMany(KunjunganKerja::class, 'kunjungan_peserta', 'id_anggota', 'id_kunjungan');
    }
}
