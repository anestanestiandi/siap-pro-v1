<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Tabel yang digunakan oleh model.
     */
    protected $table = 'tb_user';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'role',
        'is_active',
        'jenis_kelamin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check apakah user adalah Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check apakah user adalah User Eksternal.
     */
    public function isEksternal(): bool
    {
        return $this->role === 'eksternal';
    }

    /**
     * Check apakah user bisa melakukan input/edit/delete (Super Admin atau Admin).
     */
    public function canManageData(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Relasi ke user_details.
     */
    public function detail()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id_user');
    }
}
