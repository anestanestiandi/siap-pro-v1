<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrasiPerjalananDinas extends Model
{
    protected $table = 'administrasi_perjalanan_dinas';
    protected $primaryKey = 'id_adm_perjalanan_dinas';

    protected $fillable = [
        'id_jenis_perjalanan_dinas',
        'id_petugas',
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu',
        'tujuan',
        'pelaksana',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'id_petugas' => 'array',
    ];

    public function petugasProtokol()
    {
        return $this->belongsTo(MasterPetugasProtokol::class, 'id_petugas', 'id_petugas');
    }

    public function jenisPerjalananDinas()
    {
        return $this->belongsTo(MasterJenisPerjalananDinas::class, 'id_jenis_perjalanan_dinas', 'id_jenis_perjalanan');
    }

    public function getPetugasAttribute()
    {
        $ids = is_string($this->id_petugas) ? json_decode($this->id_petugas) : $this->id_petugas;
        return MasterPetugasProtokol::whereIn('id_petugas', (array)($ids ?: []))->get();
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
