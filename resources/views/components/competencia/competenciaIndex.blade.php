<?php

use Livewire\Component;

new class extends Component
{
    public $search = '';
    public $competencias = [];

    public function mount()
    {
        $this->cargarCompetencia();
    }

    public function updatedSearch()
    {
        $this->cargarCompetencia();
    }

    public function cargarCompetencia()
    {
        $this->competencias = DB::table('competencia')
        ->where('com_descripcion', 'like', '%' . $this->search . '%')
            ->orderBy('com_id', 'desc')
            ->get();
    }

    public function guardarCambios()
    {
        foreach ($this->competencias as $competencia) {
            if(trim($competencia->com_descripcion) == ""){
                continue;
            }
            if (!empty($competencia->com_id)) {
                DB::table('competencia')
                    ->where('com_id', $competencia->com_id)
                    ->update([
                        'com_descripcion' => $competencia->com_descripcion
                    ]);
            }else {
                DB::table('competencia')->insert([
                    'com_descripcion' => $competencia->com_descripcion,
                    'created_at' => now(),
                ]);
            }
        }

        session()->flash('success', 'Actualizados exitosa');

        $this->cargarCompetencia();
    }

    public function eliminar($id)
    {
        DB::table('competencia')
            ->where('com_id', $id)
            ->delete();

        session()->flash('success', 'Eliminación exitosa');

        $this->cargarCompetencia();
    }

    public function agregarObjetivo()
    {
        $this->competencias[] = (object) [
            'com_id' => null,
            'com_descripcion' => null,
            'created_at' => now()
        ];
    }

    public function quitarObjetivo($index)
    {
        unset($this->competencias[$index]);
        //$this->curso_objetivo = array_values($this->curso_objetivo);
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
            <h4 class="mb-0">Competencia</h4>

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
                            <th>Competencia</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($competencias as $competencia)
                            <tr>
                                <td><input wire:model="competencias.{{ $loop->index }}.com_descripcion" class="form-control" type="text" value="{{ $competencia->com_descripcion }}"></td>
                                <td>{{ $competencia->created_at }}</td>
                                <td>
                                    @if(!empty($competencia->com_id))
                                        <button
                                        class="btn btn-danger"
                                        wire:click="eliminar({{ $competencia->com_id }})"
                                        wire:confirm="¿Seguro que deseas eliminar esta competencia?">Eliminar</button>
                                    @else
                                        <button class="btn btn-danger"
                                            wire:click="quitarObjetivo({{ $loop->index }})">
                                            Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay resultados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-center">
                                <a class="btn btn-success" wire:click="guardarCambios">Guardar cambios</a>
                            </td>
                            <td colspan="2" class="text-end">
                                <button class="btn btn-primary mb-2" wire:click="agregarObjetivo">
                                    Crear
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
</div>