<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
        'foto_perfil',
        'area',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function personal()
    {
        return $this->hasOne(Personal::class);
    }

    public function incidenciasRegistradas()
    {
        return $this->hasMany(Incidence::class, 'registrado_por');
    }

    public function reportesGenerados()
    {
        return $this->hasMany(DailyReport::class, 'generado_por');
    }
}
