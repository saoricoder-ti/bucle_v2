<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Services\ScraperService;

class ScraperController extends BaseController
{
    use ResponseTrait;

    /**
     * Endpoint para forzar la sincronización de una placa o identificador[cite: 1]
     */
    public function sync($category, $identifier)
    {
        $service = new ScraperService();
        // Lógica para ejecutar scrapers en segundo plano mediante eventos[cite: 1]
        \CodeIgniter\Events\Events::trigger('entity_created', [
            'categoria' => $category,
            'identificador' => $identifier
        ]);

        return $this->respond(['status' => 'Sincronización iniciada']);
    }
}