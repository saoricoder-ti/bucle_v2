<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class SubcategoriaController extends ResourceController
{
    protected $modelName = 'App\Models\SubcategoriaModel';
    protected $format    = 'json';

    /**
     * Lista subcategorías filtradas por ID de categoría padre
     * GET /api/subcategorias/filter/(:num)
     */
    public function filterByCategory($catId)
    {
        try {
            $data = $this->model->where('categoria_id', $catId)->findAll();
            
            // Decodificar JSON para el frontend
            foreach ($data as &$item) {
                if (isset($item['datos_extra']) && is_string($item['datos_extra'])) {
                    $item['datos_extra'] = json_decode($item['datos_extra']);
                }
            }
            
            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Actualiza bloques y propiedades de un evento (Auto-guardado)
     * PUT /api/subcategorias/(:num)
     */
    public function update($id = null)
    {
        try {
            $json = $this->request->getJSON();
            if (!$json) {
                return $this->failValidationErrors("No se recibieron datos");
            }

            $data = [
                'updated_at'  => date('Y-m-d H:i:s')
            ];

            if (isset($json->nombre)) $data['nombre'] = $json->nombre;
            if (isset($json->emoji)) $data['emoji'] = $json->emoji;
            if (isset($json->blocks)) $data['datos_extra'] = json_encode($json->blocks);

            if ($this->model->update($id, $data)) {
                return $this->respond(['status' => 'success', 'message' => 'Sincronizado']);
            }

            return $this->fail('Error al actualizar en DB');
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Crea una nueva subcategoría vinculada a una categoría
     * POST /api/subcategorias
     */
    public function create()
    {
        try {
            $json = $this->request->getJSON();
            $data = [
                'categoria_id' => $json->categoria_id,
                'nombre'       => $json->nombre ?? 'Nuevo Evento',
                'emoji'        => $json->emoji ?? '✨',
                'descripcion'  => $json->descripcion ?? '',
                'datos_extra'  => json_encode($json->blocks ?? []),
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $id = $this->model->insert($data);
            return $this->respondCreated(['id' => $id, 'status' => 'success']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
