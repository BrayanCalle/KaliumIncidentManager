<?php

namespace App\Commands;
use LaravelZero\Framework\Commands\Command;
use App\Services\IncidentManager;

class ProcessSingleIncident extends Command
{
    // El 'id' es un argumento obligatorio
    protected $signature = 'incidents:process-one {id : El ID del incidente a procesar}';
    protected $description = 'Cambia el estado de un incidente específico a IN_PROGRESS';

    public function handle(IncidentManager $manager)
    {
        // Mensaje Principal
        $this->info('[Kalium] Sistema Gestion Incidentes iniciando');
        $id = $this->argument('id');
        try {
            // El Manager hace todo el trabajo y las validaciones
            $incident = $manager->updateStatusToInProgress($id);
            
            $this->info("✔ Incidente #{$id} actualizado a IN_PROGRESS correctamente.");
            
        } catch (\Exception $e) {
            // Aquí atrapamos el mensaje exacto que definimos en el Manager
            $this->error($e->getMessage());
        }
    }
}
