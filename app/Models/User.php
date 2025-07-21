<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pais',
        'ciudad',
        'dni',
        'celular',
        'foto',
        'verificado',
        'dni_foto',
        'dni_foto_atras',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verificado' => 'boolean', // Añadido el cast para verificado
        ];
    }

    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class);
    }

    // Métodos helper para verificación (opcional)
    public function isVerified(): bool
    {
        return $this->verificado;
    }

    public function markAsVerified(): void
    {
        $this->update(['verificado' => true]);
    }

    public function markAsUnverified(): void
    {
        $this->update(['verificado' => false]);
    }

    public function registroConductor()
{
    return $this->hasOne(\App\Models\RegistroConductor::class, 'user_id', 'id');
}
}