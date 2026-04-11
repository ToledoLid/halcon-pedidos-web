<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    // Constantes de estados
    const STATUS_ORDERED = 'ordered';
    const STATUS_IN_PROCESS = 'in_process';
    const STATUS_IN_ROUTE = 'in_route';
    const STATUS_DELIVERED = 'delivered';

    public static $statuses = [
        self::STATUS_ORDERED => 'Pedido',
        self::STATUS_IN_PROCESS => 'En Proceso',
        self::STATUS_IN_ROUTE => 'En Ruta',
        self::STATUS_DELIVERED => 'Entregado',
    ];

    protected $table = 'orders';

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_number',
        'fiscal_data',
        'order_date',
        'delivery_address',
        'notes',
        'status',
        'created_by'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relación con el usuario que creó el pedido
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relación con todas las fotos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Relación para obtener la foto de carga (la más reciente)
    public function loading_photo()
    {
        return $this->hasOne(Photo::class)->where('photo_type', 'loading')->latest();
    }

    // Relación para obtener la foto de entrega (la más reciente)
    public function delivery_photo()
    {
        return $this->hasOne(Photo::class)->where('photo_type', 'delivery')->latest();
    }

    // Accessor para obtener el label del estado
    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }
}