<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinoConductor extends Model
{
    use HasFactory;

    protected $fillable = [
        'conductor_id',
        'latitud',
        'longitud',
    ];
}

