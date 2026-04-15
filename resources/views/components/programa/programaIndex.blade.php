<?php

use Livewire\Component;

new class extends Component
{
    public $programas;
    public $search = '';

    public function mount()
    {
        $this->cargarProgramas();
    }

    public function updatedSearch()
    {
        $this->cargarProgramas();
    }

    public function cargarProgramas()
    {
        $this->programas = DB::table('programa_academico')
            ->where('pro_aca_descripcion', 'like', '%' . $this->search . '%')
            ->orderBy('pro_aca_id', 'desc')
            ->get();
    }
};
?>

<div>
    <div class="container my-4">

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Programas Académicos</h4>

            <!-- 🔍 Buscador -->
            <input 
                type="text" 
                class="form-control w-50" 
                placeholder="Buscar..." 
                wire:model.live="search"
            >
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Programa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programas as $programa)
                            <tr>
                                <td>{{ $programa->pro_aca_id }}</td>
                                <td>{{ $programa->pro_aca_descripcion }}</td>
                                <td><a href="{{ url('/programaDetalle/' . $programa->pro_aca_id) }}" class="btn btn-success" wire:navigate>Ver</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No hay resultados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>