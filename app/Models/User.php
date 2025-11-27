<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'ADMIN' || $this->role === 'admin';
    }

    public function isJefeDeRed()
    {
        return $this->role === 'JefeDeRed' || $this->role === 'jefe_de_red';
    }

    public function isCoordinadorDeMicroRed()
    {
        return $this->role === 'CoordinadorDeMicroRed' || $this->role === 'coordinador_de_microred';
    }

    /**
     * Relación con solicitudes
     */
    public function solicitud()
    {
        return $this->hasOne(Solicitud::class, 'user_id');
    }
}