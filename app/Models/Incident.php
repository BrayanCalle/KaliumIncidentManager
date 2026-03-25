<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    // Mapeo para cumplir con el nombre exacto solicitado: createdAt
    const CREATED_AT = 'createdAt';

    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'priority'
    ];

    // Esto asegura que al convertir a array/JSON se use el nombre correcto
    protected $casts = [
        'createdAt' => 'datetime',
    ];
}
