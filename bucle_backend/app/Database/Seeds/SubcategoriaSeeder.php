<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubcategoriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'categoria_id' => 1, // Mascotas
                'nombre'       => 'Luna (Golden Retriever)',
                'emoji'        => '🐕',
                'descripcion'  => 'Seguimiento de vacunas y alimentación.',
                'datos_extra'  => json_encode([
                    ['id' => 1, 'type' => 'text', 'content' => 'Historial médico de Luna', 'style' => 'h1'],
                    ['id' => 2, 'type' => 'text', 'content' => 'Vacuna Parvovirus aplicada el 01/05/2026', 'style' => 'p']
                ])
            ],
            [
                'categoria_id' => 2, // Salud
                'nombre'       => 'Chequeo Anual 2026',
                'emoji'        => '🩺',
                'descripcion'  => 'Resultados de exámenes de sangre y cardiología.',
                'datos_extra'  => json_encode([
                    ['id' => 1, 'type' => 'text', 'content' => 'Resultados Generales', 'style' => 'h1'],
                    ['id' => 2, 'type' => 'table', 'content' => 'Colesterol,180;Glucosa,90', 'style' => 'p']
                ])
            ]
        ];

        // Insertar datos en la tabla 'subcategorias'
        $this->db->table('subcategorias')->insertBatch($data);
    }
}
