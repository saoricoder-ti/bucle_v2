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
        try {
            $json = $this->request->getJSON();
            
            if (!$json || !isset($json->nombre)) {
                return $this->fail('El nombre de la categoría es obligatorio', 400);
            }

            $data = [
                'nombre' => $json->nombre,
                'emoji'  => $json->emoji ?? '📁',
                'color'  => $json->color ?? '#6366f1',
                'schema_config' => json_encode([
                    ['id' => 'gen-' . time(), 'type' => 'text', 'content' => '', 'style' => 'p']
                ])
            ];

            if ($this->model->insert($data)) {
                return $this->respondCreated([
                    'status' => 'success',
                    'message' => 'Categoría creada correctamente',
                    'data' => $data
                ]);
            }

            return $this->fail('No se pudo guardar la categoría en la base de datos');

        } catch (\Exception $e) {
            log_message('error', '[CategoriaController::create] ' . $e->getMessage());
            return $this->failServerError('Error interno del servidor: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el esquema dinámico (Notion style) basado en la categoría
     * GET /api/categorias/schema/(:num)
     */
    public function getSchema($id) {
        try {
            $categoria = $this->model->find($id);

            if (!$categoria) {
                return $this->failNotFound("Categoría no encontrada");
            }

            // Normalizamos el nombre para buscar el conector (ej: Mascotas -> MascotaConnector)
            $nombreLimpio = rtrim(ucfirst($categoria['nombre']), 's');
            $connectorClass = "App\\Libraries\\Connectors\\" . $nombreLimpio . "Connector";
            
            if (!class_exists($connectorClass)) {
                 // Si no existe, devolvemos el esquema que tenga guardado en su 'schema_config'
                 // O uno genérico si está vacío
                 $defaultSchema = json_decode($categoria['schema_config'] ?? '[]');
                 return $this->respond([
                     'fields' => !empty($defaultSchema) ? $defaultSchema : [['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'text']]
                 ]);
            }

            $connector = new $connectorClass();
            return $this->respond($connector->getUISchema());

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
