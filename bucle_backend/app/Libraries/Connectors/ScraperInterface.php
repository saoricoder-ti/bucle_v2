<?php

namespace App\Libraries\Connectors;

interface ScraperInterface
{
    /**
     * Ejecuta la extracción de datos basada en el identificador.
     */
    public function parse(string $identifier): array;
}