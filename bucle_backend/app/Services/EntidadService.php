<?php

namespace App\Services;

use App\Repositories\EntidadRepository;
use App\Libraries\Connectors\AutoConnector;
use App\Libraries\Connectors\MascotaConnector;
use App\Libraries\Connectors\SaludConnector;

class EntidadService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new EntidadRepository();
    }

    /**
     * Lógica para obtener el conector correcto según la categoría[cite: 1]
     */
    public function getConnector(string $category)
    {
        $map = [
            'autos'    => new AutoConnector(),
            'mascotas' => new MascotaConnector(),
            'salud'    => new SaludConnector(),
        ];

        return $map[$category] ?? null;
    }
}