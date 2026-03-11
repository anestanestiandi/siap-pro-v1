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
        'id_anggota' => 'array',
        'id_petugas' => 'array',
    ];

    public function jenisKunjungan()
    {
        return $this->belongsTo(MasterJenisKunjungan::class, 'id_jenis_kunjungan', 'id_jenis_kunjungan');
    }

    public function getAnggotaDewanAttribute()
    {
        $ids = is_string($this->id_anggota) ? json_decode($this->id_anggota) : $this->id_anggota;
        return MasterAnggotaDewan::whereIn('id_anggota', (array)($ids ?: []))->get();
    }

    public function getPetugasAttribute()
    {
        $ids = is_string($this->id_petugas) ? json_decode($this->id_petugas) : $this->id_petugas;
        return MasterPetugasProtokol::whereIn('id_petugas', (array)($ids ?: []))->get();
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
