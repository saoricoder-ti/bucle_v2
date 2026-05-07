<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcategoriaModel extends Model
{
    protected $table      = 'subcategorias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['categoria_id', 'nombre', 'emoji', 'color', 'descripcion', 'datos_extra'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
