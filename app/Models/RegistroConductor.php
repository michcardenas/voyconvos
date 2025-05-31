<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroConductor extends Model
{
    use HasFactory;

    protected $table = 'registro_conductores';

    protected $fillable = [
        'user_id',
        'marca_vehiculo',
        'modelo_vehiculo',
        'anio_vehiculo',
        'patente',
        'licencia',
        'cedula',
        'cedula_verde',
        'seguro',
        'rto',
        'antecedentes',
        'estado_verificacion',
        'estado_registro',
    ];

    /**
     * Relación con el usuario dueño del registro.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesores para obtener URL pública de los archivos.
     */
    public function getLicenciaUrlAttribute()
    {
        return $this->licencia ? asset('storage/' . $this->licencia) : null;
    }

    public function getCedulaUrlAttribute()
    {
        return $this->cedula ? asset('storage/' . $this->cedula) : null;
    }

    public function getCedulaVerdeUrlAttribute()
    {
        return $this->cedula_verde ? asset('storage/' . $this->cedula_verde) : null;
    }

    public function getSeguroUrlAttribute()
    {
        return $this->seguro ? asset('storage/' . $this->seguro) : null;
    }

    public function getRtoUrlAttribute()
    {
        return $this->rto ? asset('storage/' . $this->rto) : null;
    }

    public function getAntecedentesUrlAttribute()
    {
        return $this->antecedentes ? asset('storage/' . $this->antecedentes) : null;
    }
}
