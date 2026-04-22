<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

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
        'created_by',
        'archived'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'deleted_at' => 'datetime',
        'archived' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function loading_photo()
    {
        return $this->hasOne(Photo::class)->where('photo_type', 'loading')->latest();
    }

    public function delivery_photo()
    {
        return $this->hasOne(Photo::class)->where('photo_type', 'delivery')->latest();
    }

    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    public function archive()
    {
        $this->archived = true;
        $this->save();
    }

    public function unarchive()
    {
        $this->archived = false;
        $this->save();
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }
}