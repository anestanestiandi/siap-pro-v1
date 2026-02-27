<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganKerja extends Model
{
    protected $table = 'kunjungan_kerja';
    protected $primaryKey = 'id_kunjungan';
    protected $guarded = ['id_kunjungan'];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'rombongan' => 'array',
    ];

    public function jenisKunjungan()
    {
        return $this->belongsTo(MasterJenisKunjungan::class, 'id_jenis_kunjungan', 'id_jenis_kunjungan');
    }

    public function anggotaDewan()
    {
        return $this->belongsToMany(MasterAnggotaDewan::class, 'kunjungan_peserta', 'id_kunjungan', 'id_anggota')
                    ->withTimestamps();
    }

    public function petugas()
    {
        return $this->belongsToMany(MasterPetugasProtokol::class, 'kunjungan_petugas', 'id_kunjungan', 'id_petugas')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    public function singlePetugas()
    {
        return $this->belongsTo(MasterPetugasProtokol::class, 'id_petugas', 'id_petugas');
    }

    public function singleAnggota()
    {
        return $this->belongsTo(MasterAnggotaDewan::class, 'id_anggota', 'id_anggota');
    }

    public function provinsi()
    {
        return $this->belongsTo(MasterProvinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    public function historyLogs()
    {
        return $this->morphMany(HistoryLog::class, 'model')->latest();
    }
}
