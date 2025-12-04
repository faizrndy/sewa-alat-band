<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'password',
        'role',
        'otp',              // <-- WAJIB DITAMBAHKAN
        'otp_expires_at',   // <-- WAJIB DITAMBAHKAN
        'is_verified',      // <-- WAJIB DITAMBAHKAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',              // optional
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }
}
