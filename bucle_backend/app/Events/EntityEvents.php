<?php

namespace App\Events;

use App\Services\ScraperService;
use App\Libraries\Connectors\AutoConnector;
use App\Repositories\EntidadRepository;

class EntityEvents
{
    public static function runScrapers(array $payload)
    {
        $id = $payload['identificador'];
        $categoria = $payload['categoria'];
        
        // 1. Instanciar el servicio y el repositorio
        $scraperService = new ScraperService();
        $repository = new EntidadRepository();

        // 2. Elegir el conector según la categoría
        if ($categoria === 'autos') {
            $connector = new AutoConnector();
            $scraperService->setConnector($connector);
            
            // 3. Ejecutar la sincronización
            $freshData = $scraperService->sync($id);
            
            // 4. Actualizar Supabase con la nueva información (Ficha, Multas, etc.)
            // Fusionamos los datos manuales del frontend con los del scraper
            $repository->updateEntityData($categoria, $id, $freshData['info']);
        }
        
        // Para 'mascotas' u otras, podrías no necesitar scrapers o usar unos diferentes
    }
}