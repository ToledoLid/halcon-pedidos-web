<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'uploaded_by');
    }

    // Métodos de verificación de roles
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isSales()
    {
        return $this->role && $this->role->name === 'sales';
    }

    public function isWarehouse()
    {
        return $this->role && $this->role->name === 'warehouse';
    }

    public function isRoute()
    {
        return $this->role && $this->role->name === 'route';
    }

    public function isPurchasing()
    {
        return $this->role && $this->role->name === 'purchasing';
    }
}