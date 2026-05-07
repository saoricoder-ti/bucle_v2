<?php

namespace App\Services;

class ScraperService
{
    protected array $scrapers = [];

    /**
     * Registra los scrapers necesarios para el flujo actual.
     */
    public function addScraper(\App\Libraries\Connectors\ScraperInterface $scraper)
    {
        $this->scrapers[] = $scraper;
    }

    /**
     * Ejecuta todos los scrapers registrados y consolida la información.
     */
    public function runAll(string $identifier): array
    {
        $consolidatedData = [];
        
        foreach ($this->scrapers as $scraper) {
            try {
                $result = $scraper->parse($identifier);
                $consolidatedData = array_merge($consolidatedData, $result);
            } catch (\Exception $e) {
                log_message('error', 'Fallo en scraper: ' . $e->getMessage());
            }
        }

        return $consolidatedData;
    }
}