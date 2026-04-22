<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'stock',
        'price',
        'category',
        'unit',
        'min_stock',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock' => 'integer',
        'min_stock' => 'integer'
    ];

    // Verificar si el stock es bajo
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    // Scope para productos activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope para productos con bajo stock
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock');
    }

    // Scope para búsqueda
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', '%' . $term . '%')
                     ->orWhere('code', 'like', '%' . $term . '%');
    }
}