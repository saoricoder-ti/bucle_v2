<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class CategoriaController extends ResourceController
{
    protected $modelName = 'App\Models\CategoriaModel';
    protected $format    = 'json';

    /**
     * Devuelve todas las categorías base (Mascotas, Autos, Salud, etc.)
     * GET /api/categorias
     */
    public function index()
    {
        try {
            $data = $this->model->findAll();
            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Registra una nueva categoría genérica
     * POST /api/categorias
     */
    public function create()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->nombre)) {
            return $this->fail('Datos incompletos');
        }

        $data = [
            'nombre' => $json->nombre,
            'emoji'  => $json->emoji ?? '📁',
            'color'  => $json->color ?? '#6366f1',
            // Al ser genérico, le damos bloques vacíos iniciales para que el EditorCanvas funcione
            'schema_config' => json_encode([
                ['id' => 'gen-' . time(), 'type' => 'text', 'content' => '', 'style' => 'p']
            ])
        ];

        try {
            if ($this->model->insert($data)) {
                return $this->respondCreated(['status' => 'OK']);
            }
            return $this->fail('Error al insertar en la base de datos');
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
