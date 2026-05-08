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
            if (isset($json->color)) $data['color'] = $json->color;
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
            
            if (!$json || !isset($json->categoria_id)) {
                return $this->fail('La categoría padre (categoria_id) es obligatoria', 400);
            }

            $data = [
                'categoria_id' => $json->categoria_id,
                'nombre'       => $json->nombre ?? 'Nuevo Evento',
                'emoji'        => $json->emoji ?? '✨',
                'color'        => $json->color ?? '#6366f1',
                'descripcion'  => $json->descripcion ?? '',
                'datos_extra'  => json_encode($json->blocks ?? [
                    ['id' => 'block-' . time(), 'type' => 'text', 'content' => '', 'style' => 'p']
                ])
            ];

            $id = $this->model->insert($data);
            
            if ($id) {
                return $this->respondCreated([
                    'status'  => 'success',
                    'message' => 'Evento creado correctamente',
                    'id'      => $id,
                    'data'    => $data
                ]);
            }

            return $this->fail('No se pudo crear el evento en la base de datos');

        } catch (\Exception $e) {
            log_message('error', '[SubcategoriaController::create] ' . $e->getMessage());
            return $this->failServerError('Error interno: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una subcategoría (Evento)
     * DELETE /api/subcategorias/(:num)
     */
    public function delete($id = null) {
        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted(['status' => 'success']);
            }
            return $this->fail("No se pudo eliminar");
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Duplica un evento con todos sus bloques
     * POST /api/subcategorias/duplicate/(:num)
     */
    public function duplicate($id) {
        try {
            $original = $this->model->find($id);
            if (!$original) return $this->failNotFound();

            unset($original['id']);
            $original['nombre'] .= " (Copia)";
            $original['created_at'] = date('Y-m-d H:i:s');

            if ($newId = $this->model->insert($original)) {
                return $this->respondCreated(['status' => 'success', 'id' => $newId]);
            }
            return $this->fail("Error al duplicar evento");
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
