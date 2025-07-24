<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetadatoPagina extends Model
{
    protected $table = 'metadatos_paginas';

    protected $fillable = [
        'pagina',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'meta_robots',
        'extra_meta',
    ];
}
