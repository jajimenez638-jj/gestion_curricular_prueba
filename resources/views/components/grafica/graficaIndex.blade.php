<?php

use Livewire\Component;

new class extends Component
{
    public $porcentajeObjetivosSinAsignar = 0;
    public $porcentajeCompetenciasSinObjetivos = 0;

    public function mount()
    {
        $this->cargarGrafica();
    }

    public function cargarGrafica()
    {
        $totalObjetivos = DB::table('objetivo')->count();

        $objetivosAsignados = DB::table('curso_objetivo')
            ->selectRaw('COUNT(DISTINCT obj_id) as total')
            ->value('total');

        $objetivosSinAsignar = $totalObjetivos - $objetivosAsignados;

        $this->porcentajeObjetivosSinAsignar = $totalObjetivos > 0
            ? round(($objetivosSinAsignar / $totalObjetivos) * 100, 2)
            : 0;

        // =====================

        $totalCompetencias = DB::table('competencia')->count();

        $competenciasConObjetivos = DB::table('objetivo')
            ->selectRaw('COUNT(DISTINCT com_id) as total')
            ->value('total');

        $competenciasSinObjetivos = $totalCompetencias - $competenciasConObjetivos;

        $this->porcentajeCompetenciasSinObjetivos = $totalCompetencias > 0
            ? round(($competenciasSinObjetivos / $totalCompetencias) * 100, 2)
            : 0;
    }
};
?>

<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Gráficas</h4>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5>Objetivos sin asignar a cursos</h5>
                                <h2>{{ $porcentajeObjetivosSinAsignar }}%</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5>Competencias sin objetivos asociados</h5>
                                <h2>{{ $porcentajeCompetenciasSinObjetivos }}%</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5>Objetivos</h5>
                            <canvas id="chartObjetivos" wire:ignore></canvas>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5>Competencias</h5>
                            <canvas id="chartCompetencias" wire:ignore></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function renderCharts() {
            const el1 = document.getElementById('chartObjetivos');
            const el2 = document.getElementById('chartCompetencias');

            if (!el1 || !el2) return;

            new Chart(el1, {
                type: 'doughnut',
                data: {
                    labels: ['Sin asignar', 'Asignados'],
                    datasets: [{
                        data: [
                            {{ $porcentajeObjetivosSinAsignar }},
                            {{ 100 - $porcentajeObjetivosSinAsignar }}
                        ],
                        backgroundColor: ['#dc3545', '#28a745']
                    }]
                }
            });

            new Chart(el2, {
                type: 'doughnut',
                data: {
                    labels: ['Sin objetivos', 'Con objetivos'],
                    datasets: [{
                        data: [
                            {{ $porcentajeCompetenciasSinObjetivos }},
                            {{ 100 - $porcentajeCompetenciasSinObjetivos }}
                        ],
                        backgroundColor: ['#ffc107', '#198754']
                    }]
                }
            });
        }

        // 👇 clave
        document.addEventListener('livewire:navigated', () => {
            setTimeout(renderCharts, 100);
        });

        document.addEventListener('livewire:load', () => {
            setTimeout(renderCharts, 100);
        });
    </script>
</div>
