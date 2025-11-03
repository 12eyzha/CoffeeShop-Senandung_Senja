<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2', // otomatis format angka harga
    ];

    /**
     * 🔹 Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
