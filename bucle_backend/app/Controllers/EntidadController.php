<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Services\ScraperService;
use App\Libraries\Connectors\MascotaConnector;
use App\Repositories\EntidadRepository;

class EntidadController extends BaseController
{
    use ResponseTrait;

    protected $scraperService;
    protected $repository;

    public function __construct()
    {
        $this->scraperService = new ScraperService();
        $this->repository = new EntidadRepository();
    }

    /**
     * Obtiene el esquema dinámico (tipo Notion) para una categoría
     * GET /api/config/schema/(:segment)
     */
// En el método getSchema($category) de EntidadController:

    public function getSchema($category)
    {
        $connectors = [
            'mascotas' => new \App\Libraries\Connectors\MascotaConnector(),
            'autos'    => new \App\Libraries\Connectors\AutoConnector(),
            // Para agregar 'Salud', solo crearías el archivo y lo añadirías aquí
        ];

        if (isset($connectors[$category])) {
            return $this->respond($connectors[$category]->getUISchema());
        }

        return $this->failNotFound("Categoría '$category' no configurada.");
    }

    /**
     * Guarda una nueva entidad con sus campos dinámicos
     * POST /api/entidades/registrar
     */
    public function store()
    {
        $rules = [
            'categoria'     => 'required',
            'identificador' => 'required',
            'campos'        => 'required' // El JSON dinámico
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'categoria'         => $this->request->getVar('categoria'),
            'identificador'     => $this->request->getVar('identificador'),
            'campos_dinamicos'  => $this->request->getVar('campos'),
        ];

        try {
            $this->repository->saveEntity($data);
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Entidad registrada en el Bucle'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }

        $payload = [
        'categoria'     => $this->request->getVar('categoria'),
        'identificador' => $this->request->getVar('identificador'),
        'campos'        => $this->request->getVar('campos'),
        ];

        try {
            // Guardar el registro inicial
            $this->repository->saveEntity($payload);

            // 🔥 DISPARAR EL EVENTO ASÍNCRONO
            // CodeIgniter lanza esto y sigue su camino
            \CodeIgniter\Events\Events::trigger('entity_created', $payload);

            return $this->respondCreated([
                'status'  => 'processing',
                'message' => 'Registro creado. Sincronizando datos oficiales en segundo plano.'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
    
}