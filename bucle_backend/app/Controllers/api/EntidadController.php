<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Repositories\EntidadRepository;

class EntidadController extends BaseController
{
    use ResponseTrait;

    protected $repository;

    public function __construct()
    {
        $this->repository = new EntidadRepository();
    }

    /**
     * Obtiene el esquema dinámico (Notion style)
     */
    public function getSchema($id) {
        try {
            // Buscamos la categoría para saber qué conector usar
            $model = new \App\Models\CategoriaModel();
            $categoria = $model->find($id);

            if (!$categoria) {
                return $this->failNotFound("Categoría no encontrada");
            }

            // Normalizamos el nombre para buscar el conector (ej: Mascotas -> MascotaConnector)
            $nombreLimpio = rtrim(ucfirst($categoria['nombre']), 's');
            $connectorClass = "App\\Libraries\\Connectors\\" . $nombreLimpio . "Connector";
            
            if (!class_exists($connectorClass)) {
                 // Si no existe, devolvemos un esquema genérico para que no explote la app
                 return $this->respond([
                     'fields' => [['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'text']]
                 ]);
            }

            $connector = new $connectorClass();
            return $this->respond($connector->getUISchema());

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Registra una nueva entidad
     */
    public function store()
    {
        try {
            $data = $this->request->getJSON(true);
            
            $this->repository->saveEntity([
                'categoria' => $data['categoria'] ?? 'default',
                'identificador' => $data['identificador'] ?? 'ID-' . time(),
                'campos_dinamicos' => $data['campos'] ?? []
            ]);

            return $this->respondCreated(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Obtiene el detalle completo de una entidad por su ID
     * GET /api/entidades/detalle/(:segment)
     */
    public function show($id = null)
    {
        try {
            $entidad = $this->repository->getById($id);
            
            if (!$entidad) {
                return $this->failNotFound("Bucle no encontrado");
            }

            // Cargamos el esquema de la categoría para que el frontend sepa cómo dibujar los datos
            $connectors = [
                'mascotas' => new \App\Libraries\Connectors\MascotaConnector(),
                'autos'    => new \App\Libraries\Connectors\AutoConnector(),
            ];

            if (!isset($connectors[$entidad['categoria']])) {
                return $this->failNotFound("Configuración de categoría no encontrada");
            }

            $schema = $connectors[$entidad['categoria']]->getUISchema();

            return $this->respond([
                'entidad' => $entidad,
                'schema'  => $schema
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Obtiene todas las entidades registradas
     * GET /api/entidades
     */
    public function index()
    {
        try {
            $entidades = $this->repository->getAll(); 
            return $this->respond($entidades);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Actualiza los bloques de datos extra de una entidad (Editor Notion-style)
     * PUT /api/entidades/update-blocks/(:segment)
     */
    public function updateBlocks($id = null)
    {
        try {
            $data = $this->request->getJSON(true);
            $blocks = $data['datos_extra'] ?? null;

            if ($blocks === null) {
                return $this->failValidationErrors("No se proporcionaron datos para actualizar");
            }

            $this->repository->updateDatosExtra($id, $blocks);

            return $this->respond([
                'status' => 'success',
                'message' => 'Bloques sincronizados correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Obtener todas las Categorías (Banner Izquierdo)
     */
    public function getCategories() {
        $model = new \App\Models\CategoriaModel();
        return $this->respond($model->findAll());
    }

    /**
     * Obtener Subcategorías filtradas (Dashboard Central)
     */
    public function getSubcategories($categoriaId) {
        $model = new \App\Models\SubcategoriaModel();
        $data = $model->where('categoria_id', $categoriaId)->findAll();
        
        // Decodificamos el JSONB para que el frontend lo lea como objeto
        foreach ($data as &$item) {
            if (isset($item['datos_extra']) && is_string($item['datos_extra'])) {
                $item['datos_extra'] = json_decode($item['datos_extra']);
            }
        }
        return $this->respond($data);
    }

    /**
     * Actualiza una subcategoría (Auto-guardado del editor)
     */
    public function updateSubcategory($id = null) {
        try {
            $model = new \App\Models\SubcategoriaModel();
            $data = $this->request->getJSON(true);

            $updateData = [];
            if (isset($data['nombre'])) $updateData['nombre'] = $data['nombre'];
            if (isset($data['descripcion'])) $updateData['descripcion'] = $data['descripcion'];
            if (isset($data['emoji'])) $updateData['emoji'] = $data['emoji'];
            if (isset($data['blocks'])) $updateData['datos_extra'] = json_encode($data['blocks']);

            if (empty($updateData)) {
                return $this->failValidationErrors("No hay datos para actualizar");
            }

            $model->update($id, $updateData);

            return $this->respond(['status' => 'success', 'message' => 'Subcategoría actualizada']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}