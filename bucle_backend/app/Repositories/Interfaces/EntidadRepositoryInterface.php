<?php

namespace App\Repositories;

class EntidadRepository
{
    protected $db;

    public function __construct()
    {
        // Conexión estándar de CodeIgniter configurada para PostgreSQL/Supabase
        $this->db = \Config\Database::connect();
    }

    public function saveEntity(array $data)
    {
        $builder = $this->db->table('entidades');
        
        return $builder->insert([
            'categoria'     => $data['categoria'],
            'identificador' => $data['identificador'],
            // Convertimos el array de campos de Vue a JSON para PostgreSQL
            'datos_extra'   => json_encode($data['campos_dinamicos']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);
    }
}