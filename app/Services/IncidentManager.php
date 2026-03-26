<?php

namespace App\Services;

use App\Models\Incident;

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

    public function updateStatusToInProgress($id)
    {
        $incident = Incident::find($id);

        // Retornamos el objeto o false para que el comando decida qué mensaje mostrar
        if (!$incident) {
            throw new \Exception("No se encontró el incidente con ID: {$id}");
        }

        if ($incident->status !== 'OPEN') {
            throw new \Exception("El incidente {$id} ya está en estado: {$incident->status}");
        }

        $incident->status = 'IN_PROGRESS';
        $incident->save();

        return $incident;
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