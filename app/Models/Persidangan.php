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
    ];

    public function jenisPersidangan()
    {
        return $this->belongsTo(MasterJenisPersidangan::class, 'id_jenis_persidangan', 'id_jenis_persidangan');
    }

    public function petugasProtokol()
    {
        return $this->belongsTo(MasterPetugasProtokol::class, 'id_petugas', 'id_petugas');
    }

    public function anggotaDewan()
    {
        return $this->belongsToMany(MasterAnggotaDewan::class, 'persidangan_anggota_dewan', 'id_persidangan', 'id_anggota')
                    ->withTimestamps();
    }

    public function petugas()
    {
        return $this->belongsToMany(MasterPetugasProtokol::class, 'persidangan_petugas', 'id_persidangan', 'id_petugas')
                    ->withTimestamps();
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
