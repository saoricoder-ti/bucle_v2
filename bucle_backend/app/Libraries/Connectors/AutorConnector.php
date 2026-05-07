<?php

namespace App\Libraries\Connectors;

class AutoConnector implements ConnectorInterface
{
    public function getUISchema(): array
    {
        return [
            'category' => 'Vehículos',
            'icon' => 'pi-car',
            'fields' => [
                ['name' => 'marca', 'label' => 'Marca', 'type' => 'text', 'required' => true],
                ['name' => 'modelo', 'label' => 'Modelo', 'type' => 'text', 'required' => true],
                ['name' => 'color', 'label' => 'Color', 'type' => 'text'],
                ['name' => 'anio', 'label' => 'Año de Fabricación', 'type' => 'text'],
                ['name' => 'cilindraje', 'label' => 'Cilindraje (cc)', 'type' => 'text']
            ],
            'steps' => [
                ['id' => 1, 'label' => 'Ficha Técnica', 'icon' => 'pi-search'],
                ['id' => 2, 'label' => 'Pago Matrícula', 'icon' => 'pi-money-bill'],
                ['id' => 3, 'label' => 'Validación Multas', 'icon' => 'pi-exclamation-triangle'],
                ['id' => 4, 'label' => 'Turno RTV', 'icon' => 'pi-calendar-plus']
            ]
        ];
    }

// Dentro de app/Libraries/Connectors/AutoConnector.php

    public function fetchMetadata(string $identifier): array
    {
        $service = new \App\Services\ScraperService();
        
        // Inyectamos solo los scrapers que este conector necesita
        $service->addScraper(new \App\Libraries\Scrapers\FichaTecnicaScraper());
        $service->addScraper(new \App\Libraries\Scrapers\AntMultasScraper());
        
        return $service->runAll($identifier);
    }

    public function validateCompliance(string $identifier): array
    {
        // Simulación de estados para la placa
        return [
            ['step_id' => 1, 'completed' => true],
            ['step_id' => 2, 'completed' => false],
            ['step_id' => 3, 'completed' => false],
            ['step_id' => 4, 'completed' => false],
        ];
    }
}