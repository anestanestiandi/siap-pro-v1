<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPersidangan extends Model
{
    protected $table = 'master_jenis_persidangan';
    protected $primaryKey = 'id_jenis_persidangan';

    protected $fillable = ['nama_jenis', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke tabel persidangan.
     */
    public function persidangan()
    {
        return $this->hasMany(Persidangan::class, 'id_jenis_persidangan', 'id_jenis_persidangan');
    }
}
