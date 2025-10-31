<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ untuk login
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'address',
        'date_of_birth',
        'position',
        'join_date',
        'salary',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'position' => 'admin', // default jabatan
        'status' => 'active',  // default status aktif
    ];

    /**
     * ✅ Hash otomatis password setiap kali diisi,
     * dan hindari double-hash jika sudah terenkripsi.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // cek kalau password belum di-hash (belum diawali $2y$ atau bcrypt prefix)
            if (!str_starts_with($value, '$2y$')) {
                $value = Hash::make($value);
            }
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Relasi ke model User (opsional)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
