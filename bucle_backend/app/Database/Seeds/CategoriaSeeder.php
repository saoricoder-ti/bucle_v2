<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre' => 'Mascotas',
                'emoji'  => '🐶',
                'color'  => '#6366f1' // Indigo
            ],
            [
                'nombre' => 'Salud',
                'emoji'  => '🏥',
                'color'  => '#f43f5e' // Rose
            ],
            [
                'nombre' => 'Vehículos',
                'emoji'  => '🚗',
                'color'  => '#f59e0b' // Amber
            ],
            [
                'nombre' => 'Legal',
                'emoji'  => '⚖️',
                'color'  => '#10b981' // Emerald
            ]
        ];

        // Insertar datos en la tabla 'categorias'
        $this->db->table('categorias')->insertBatch($data);
    }
}
