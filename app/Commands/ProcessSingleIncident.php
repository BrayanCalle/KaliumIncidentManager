<?php

namespace App\Commands;
use App\Models\Incident;
use LaravelZero\Framework\Commands\Command;

class ProcessSingleIncident extends Command
{
    // El 'id' es un argumento obligatorio
    protected $signature = 'incidents:process-one {id : El ID del incidente a procesar}';
    protected $description = 'Cambia el estado de un incidente específico a IN_PROGRESS';

    public function handle()
    {
        $id = $this->argument('id');
        $incident = Incident::find($id);

        if (!$incident) {
            $this->error("No se encontró el incidente con ID: {$id}");
            return;
        }

        if ($incident->status !== 'OPEN') {
            $this->warn("El incidente {$id} ya está en estado: {$incident->status}");
            return;
        }

        $incident->status = 'IN_PROGRESS';
        $incident->save();

        $this->info("✔ Incidente #{$id} actualizado a IN_PROGRESS correctamente.");
    }
}
