<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleRead extends Model
{
    protected $fillable = [
        'id_user',
        'module_name',
        'last_read_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
