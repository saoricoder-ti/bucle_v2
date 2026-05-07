<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'emoji', 'color', 'schema_config'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
