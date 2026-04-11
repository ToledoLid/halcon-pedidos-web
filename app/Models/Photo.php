<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'order_id',
        'photo_path',
        'photo_type',
        'uploaded_by'
    ];

    // Relación con el pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con el usuario que subió la foto
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}