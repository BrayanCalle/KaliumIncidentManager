<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Services\IncidentManager;

class ProcessIncidents extends Command
{
    // Cambiamos la firma para que sea profesional
    protected $signature = 'incidents:process';
    protected $description = 'Procesa automáticamente los incidentes críticos (HIGH)';

    public function handle(IncidentManager $manager)
    {
        // 1. Mensaje obligatorio 
        $this->info('[Kalium] Sistema Gestion Incidentes iniciando');

        $this->comment('Buscando incidentes críticos pendientes...');

        // 2. Ejecutar la lógica del Service
        $updatedCount = $manager->processCriticalIncidents();

        // 3. Informar al usuario
        if ($updatedCount > 0) {
            $this->info("✔ Se han actualizado {$updatedCount} incidentes críticos a estado 'IN_PROGRESS'.");
        } else {
            $this->warn('No se encontraron incidentes críticos en estado OPEN para procesar.');
        }
    }
}
