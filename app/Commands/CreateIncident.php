<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Models\Incident;

class CreateIncident extends Command
{
    protected $signature = 'incidents:create';
    protected $description = 'Crea un nuevo incidente técnico de forma interactiva';

    public function handle()
    {
        // 1. Mensaje obligatorio
        $this->info('[Kalium] Sistema Gestion Incidentes iniciando');

        // 2. Recolectar datos de forma interactiva
        $title = $this->ask('Título del incidente');
        $description = $this->ask('Descripción detallada');
        
        // 3. Validación de Estados y Prioridades (Uso de choice para evitar estados inválidos)
        $status = $this->choice('Estado inicial', ['OPEN', 'IN_PROGRESS', 'RESOLVED'], 0);
        $priority = $this->choice('Prioridad', ['LOW', 'MEDIUM', 'HIGH'], 1);

        // 4. Lógica para evitar IDs duplicados
        // Al usar el ID autoincremental de SQLite, evitamos duplicidad técnica.
        // Si el requisito pide un ID manual, se validaría aquí.
        
        try {
            $incident = Incident::create([
                'title' => $title,
                'description' => $description,
                'status' => $status,
                'priority' => $priority,
            ]);

            $this->info("✔ Incidente #{$incident->id} creado exitosamente.");
        } catch (\Exception $e) {
            $this->error("Error al crear el incidente: " . $e->getMessage());
        }
    }
}
