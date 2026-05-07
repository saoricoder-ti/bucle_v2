<?php

namespace App\Libraries\Scrapers;

use App\Libraries\Connectors\ScraperInterface;

class AntMultasScraper implements ScraperInterface
{
    public function parse(string $identifier): array
    {
        return [
            'total_multas' => 0.00,
            'estado_legal' => 'AL_DIA',
            'fecha_caducidad' => '2027-05-05'
        ];
    }
}