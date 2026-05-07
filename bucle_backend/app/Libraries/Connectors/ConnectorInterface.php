<?php

namespace App\Libraries\Connectors;

/**
 * Interfaz para la estrategia de conectores de Bucle.
 * Permite que el sistema sea extensible (Open/Closed Principle).
 */
interface ConnectorInterface
{
    /**
     * Extrae metadata basada en un identificador (Placa, ID, etc.)
     */
    public function fetchMetadata(string $identifier): array;

    /**
     * Valida el estado de cumplimiento (Matrícula, Vacunas, etc.)
     */
    public function validateCompliance(string $identifier): array;

    /**
     * Retorna el esquema de campos dinámicos para la UI de Vue.js
     */
    public function getUISchema(): array;
}