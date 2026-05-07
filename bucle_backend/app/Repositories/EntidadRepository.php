<?php

namespace App\Repositories;

class EntidadRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // O tu cliente de Supabase
    }

    public function saveEntity(array $data)
    {
        // Guardamos en la tabla 'entidades' usando la flexibilidad de Supabase
        $builder = $this->db->table('entidades');
        
        return $builder->insert([
            'categoria' => $data['categoria'],
            'identificador' => $data['identificador'],
            'datos_extra' => json_encode($data['campos_dinamicos']), // El corazón tipo Notion
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAll()
    {
        return $this->db->table('entidades')
                        ->orderBy('updated_at', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    public function getById($id)
    {
        return $this->db->table('entidades')
                        ->where('id', $id)
                        ->get()
                        ->getRowArray();
    }
    public function updateDatosExtra(string $id, array $data)
{
    $builder = $this->db->table('entidades');
    return $builder->where('id', $id)
                   ->update([
                       'datos_extra' => json_encode($data), // Supabase recibe el JSON compacto[cite: 1]
                       'updated_at'  => date('Y-m-d H:i:s')
                   ]);
}
}