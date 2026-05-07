<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BucleEntity extends Entity
{
    // Atributos principales basados en la arquitectura Bucle
    protected $attributes = [
        'id'            => null,
        'categoria'     => null, // 'autos', 'mascotas', 'salud'
        'identificador' => null, // Placa, Chip o Cédula
        'datos_extra'   => null, // Aquí se guarda el JSONB
        'estado_actual' => 1,
    ];

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'datos_extra'   => 'json-array', // Convierte el JSON de la BD a un array de PHP
        'estado_actual' => 'integer',
    ];
}