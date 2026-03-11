<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelayananKeprotokolan extends Model
{
    protected $table = 'pelayanan_keprotokolan';
    protected $primaryKey = 'id_pelayanan';

    protected $fillable = [
        'id_pelayanan',
        'id_anggota',
        'id_jenis_pelayanan',
        'id_petugas',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'waktu',
        'waktu_selesai',
        'tempat',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'id_anggota' => 'array',
        'id_petugas' => 'array',
    ];

    public function jenisPelayanan()
    {
        return $this->belongsTo(MasterJenisPelayanan::class, 'id_jenis_pelayanan', 'id_jenis_pelayanan');
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
