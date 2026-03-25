<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Services\IncidentManager;
use App\Models\Incident;

class ListIncidents extends Command
{
    /**
     * La firma del comando.
     * Quitamos "app:" para que sea solo "list" y agregamos la opción status.
     */
    // protected $signature = 'list {--status= : Filtrar por estado (OPEN, IN_PROGRESS, RESOLVED)} {--priority= : Filtrar por prioridad (LOW, MEDIUM, HIGH)}';
    // app/Commands/ListIncidents.php
    protected $signature = 'incidents:list {--status= : Filtrar por estado} {--priority= : Filtrar por prioridad}';

    protected $description = 'Lista los incidentes técnicos de Kalium';

    public function handle(IncidentManager $manager)
    {
        // Mensaje obligatorio [cite: 3]
        $this->info('[Kalium] Sistema Gestion Incidentes iniciando');

        // Asegurar que los datos del JSON estén cargados [cite: 3]
        $manager->seedFromLocalJson();

        $status = $this->option('status');
        $priority = $this->option('priority');

        $query = Incident::query();

        // Aplicar filtros si existen [cite: 19, 27]
        if ($status) {
            $query->where('status', strtoupper($status));
        }

        if ($priority) {
            $query->where('priority', strtoupper($priority));
        }

        $incidents = $query->get(['id', 'title', 'description', 'status', 'priority', 'createdAt']);

        if ($incidents->isEmpty()) {
            $this->warn('No hay incidentes que coincidan con la búsqueda.');
            return;
        }

        // Renderizar tabla 
        $this->table(
            ['ID', 'Título', 'Descripción', 'Estado', 'Prioridad', 'Fecha'],
            $incidents->toArray()
        );
    }
}
