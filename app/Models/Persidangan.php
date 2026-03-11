<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persidangan extends Model
{
    protected $table = 'persidangan';
    protected $primaryKey = 'id_persidangan';

    protected $fillable = [
        'id_anggota',
        'id_jenis_persidangan',
        'id_petugas',
        'nama_persidangan',
        'tanggal_persidangan',
        'waktu',
        'tempat',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_persidangan' => 'date',
        'id_anggota' => 'array',
        'id_petugas' => 'array',
    ];

    public function jenisPersidangan()
    {
        return $this->belongsTo(MasterJenisPersidangan::class, 'id_jenis_persidangan', 'id_jenis_persidangan');
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
