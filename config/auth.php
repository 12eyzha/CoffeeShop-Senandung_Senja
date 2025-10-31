<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Guard dan password broker default untuk aplikasi.
    | Sekarang kita tetap pakai guard "web" seperti biasa.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Di sini kamu mendefinisikan setiap guard untuk aplikasi.
    | Guard "web" menggunakan sesi untuk login dan provider "users".
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Di sini kamu mendefinisikan bagaimana pengguna diambil dari database.
    | Kita ubah default-nya untuk pakai model Employee.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            // 🔹 Gunakan model Employee sebagai sumber autentikasi
            'model' => App\Models\Employee::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk fitur reset password. Biasanya tidak perlu diubah.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60, // token berlaku 60 menit
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Jumlah detik sebelum konfirmasi password kedaluwarsa (3 jam by default).
    |
    */

    'password_timeout' => 10800,

];
