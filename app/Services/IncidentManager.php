<?php

namespace App\Services;

use App\Models\Incident;
use Illuminate\Support\Facades\Storage;

class IncidentManager
{
    /**
     * [Kalium] Método obligatorio para procesar incidentes críticos.
     */
    public function processCriticalIncidents()
    {
        // Identificar incidentes HIGH en estado OPEN y pasarlos a IN_PROGRESS
        return Incident::where('priority', 'HIGH')
            ->where('status', 'OPEN')
            ->update(['status' => 'IN_PROGRESS']);
    }

    /**
     * Carga los datos desde el archivo JSON a la base de datos si está vacía.
     */
    public function seedFromLocalJson()
    {
        $path = storage_path('app/incidents.json');
        
        if (!file_exists($path) || Incident::count() > 0) {
            return;
        }

        $json = file_get_contents($path);
        $incidents = json_decode($json, true);

        foreach ($incidents as $data) {
            // Validación: No permitir IDs duplicados antes de insertar
            Incident::firstOrCreate(
                ['id' => $data['id']],
                [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'priority' => $data['priority'],
                ]
            );
        }
    }
}