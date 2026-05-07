<?php

namespace App\Libraries\Connectors;

class SaludConnector implements ConnectorInterface
{
    public function getUISchema(): array
    {
        return [
            'category' => 'Salud',
            'icon' => 'pi-heart-fill',
            'fields' => [
                ['name' => 'tipo_sangre', 'label' => 'Tipo de Sangre', 'type' => 'select', 'options' => ['A+', 'O+', 'AB-', 'O-']],
                ['name' => 'alergias', 'label' => 'Alergias Conocidas', 'type' => 'text'],
                ['name' => 'seguro_medico', 'label' => 'Aseguradora', 'type' => 'text']
            ],
            'steps' => [
                ['id' => 1, 'label' => 'Historia Clínica', 'icon' => 'pi-file-edit'],
                ['id' => 2, 'label' => 'Exámenes Base', 'icon' => 'pi-test-tube'],
                ['id' => 3, 'label' => 'Validación Seguro', 'icon' => 'pi-verified'],
                ['id' => 4, 'label' => 'Cita Especialista', 'icon' => 'pi-calendar']
            ]
        ];
    }

    public function fetchMetadata(string $id): array { return []; }
    public function validateCompliance(string $id): array { return []; }
}