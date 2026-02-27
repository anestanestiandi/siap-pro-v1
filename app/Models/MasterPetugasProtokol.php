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

    /**
     * Relasi ke pelayanan keprotokolan.
     */
    public function pelayanan()
    {
        return $this->belongsToMany(PelayananKeprotokolan::class, 'pelayanan_petugas', 'id_petugas', 'id_pelayanan');
    }

    /**
     * Relasi ke persidangan.
     */
    public function persidangan()
    {
        return $this->belongsToMany(Persidangan::class, 'persidangan_petugas', 'id_petugas', 'id_persidangan');
    }

    /**
     * Relasi ke administrasi perjalanan dinas.
     */
    public function perjalananDinas()
    {
        return $this->belongsToMany(AdministrasiPerjalananDinas::class, 'administrasi_perjalanan_dinas_petugas', 'id_petugas', 'id_adm_perjalanan_dinas');
    }

    /**
     * Relasi ke kunjungan kerja.
     */
    public function kunjunganKerja()
    {
        return $this->belongsToMany(KunjunganKerja::class, 'kunjungan_petugas', 'id_petugas', 'id_kunjungan');
    }
}
