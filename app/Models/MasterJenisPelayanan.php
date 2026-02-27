<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPelayanan extends Model
{
    protected $table = 'master_jenis_pelayanan';
    protected $primaryKey = 'id_jenis_pelayanan';

    protected $fillable = ['nama_jenis', 'deskripsi', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke tabel pelayanan_keprotokolan.
     */
    public function pelayananKeprotokolan()
    {
        return $this->hasMany(PelayananKeprotokolan::class, 'id_jenis_pelayanan', 'id_jenis_pelayanan');
    }
}
