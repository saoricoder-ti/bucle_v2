<?php

namespace App\Libraries\Connectors;

class MascotaConnector implements ConnectorInterface
{
    /**
     * Define qué campos necesita la UI de Vue.js para Mascotas (Estructura Notion)
     */
    public function getUISchema(): array
    {
        return [
            'category' => 'Mascotas',
            'icon' => 'pi-dog',
            'fields' => [
                ['name' => 'nombre', 'label' => 'Nombre de la Mascota', 'type' => 'text', 'required' => true],
                ['name' => 'especie', 'label' => 'Especie', 'type' => 'select', 'options' => ['Perro', 'Gato', 'Ave', 'Otro']],
                ['name' => 'fecha_nacimiento', 'label' => 'Fecha de Nacimiento', 'type' => 'date'],
                ['name' => 'ubicacion_veterinaria', 'label' => 'Veterinaria Habitual', 'type' => 'location']
            ],
            'steps' => [
                ['id' => 1, 'label' => 'Registro Inicial', 'icon' => 'pi-id-card'],
                ['id' => 2, 'label' => 'Chequeo Médico', 'icon' => 'pi-heart'],
                ['id' => 3, 'label' => 'Esquema de Vacunación', 'icon' => 'pi-shield'],
                ['id' => 4, 'label' => 'Cita de Control', 'icon' => 'pi-calendar']
            ]
        ];
    }

    public function fetchMetadata(string $identifier): array
    {
        // En Mascotas, el identificador podría ser el número de chip
        // Por ahora retornamos una estructura base
        return [
            'chip_id' => $identifier,
            'status' => 'Activo',
            'last_sync' => date('Y-m-d H:i:s')
        ];
    }

    public function validateCompliance(string $identifier): array
    {
        // Simulación de validación de pasos del Bucle
        return [
            ['step_id' => 1, 'completed' => true, 'date' => date('Y-m-d')],
            ['step_id' => 2, 'completed' => false, 'date' => null],
            ['step_id' => 3, 'completed' => false, 'date' => null],
            ['step_id' => 4, 'completed' => false, 'date' => null],
        ];
    }
}