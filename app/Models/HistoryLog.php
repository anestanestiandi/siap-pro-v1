<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'user_agent',
        'ip_address',
        'action',
        'description',
        'changes',
        'status',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
