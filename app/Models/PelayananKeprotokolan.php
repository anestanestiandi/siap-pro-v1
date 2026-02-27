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
    ];

    public function jenisPelayanan()
    {
        return $this->belongsTo(MasterJenisPelayanan::class, 'id_jenis_pelayanan', 'id_jenis_pelayanan');
    }

    public function anggotaDewan()
    {
        return $this->belongsToMany(MasterAnggotaDewan::class, 'pelayanan_anggota_dewan', 'id_pelayanan', 'id_anggota')
                    ->withTimestamps();
    }

    public function petugasProtokol()
    {
        return $this->belongsTo(MasterPetugasProtokol::class, 'id_petugas', 'id_petugas');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    public function petugas()
    {
        return $this->belongsToMany(
            MasterPetugasProtokol::class,
            'pelayanan_petugas',
            'id_pelayanan',
            'id_petugas'
        );
    }

    public function historyLogs()
    {
        return $this->morphMany(HistoryLog::class, 'model')->latest();
    }
}
