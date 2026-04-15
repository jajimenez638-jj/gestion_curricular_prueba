<?php

use Livewire\Component;

new class extends Component
{
    public $search = '';
    public $objetivos = [];
    public $competencias = [];

    public function mount()
    {
        $this->cargarObjetivos();
        $this->cargarCompetencias();
    }

    public function updatedSearch()
    {
        $this->cargarObjetivos();
    }

    public function cargarObjetivos()
    {
        $this->objetivos = DB::table('objetivo')
        ->where('obj_descripcion', 'like', '%' . $this->search . '%')
            ->orderBy('obj_id', 'desc')
            ->get();
    }

    public function guardarCambios()
    {
        foreach ($this->objetivos as $objetivo) {
            if(trim($objetivo->obj_descripcion) == "" || trim($objetivo->com_id) ==""){
                continue;
            }
            if (!empty($objetivo->obj_id)) {
                DB::table('objetivo')
                    ->where('obj_id', $objetivo->obj_id)
                    ->update([
                        'obj_descripcion' => $objetivo->obj_descripcion,
                        'com_id' => $objetivo->com_id
                    ]);
            }else {
                DB::table('objetivo')->insert([
                    'obj_descripcion' => $objetivo->obj_descripcion,
                    'com_id' => $objetivo->com_id,
                    'created_at' => now()
                ]);
            }
        }

        session()->flash('success', 'Actualizados exitosa');

        $this->cargarObjetivos();
    }

    public function eliminar($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('curso_objetivo')
                ->where('obj_id', $id)
                ->delete();

            DB::table('objetivo')
                ->where('obj_id', $id)
                ->delete();
        });

        session()->flash('success', 'Eliminación exitosa');

        $this->cargarObjetivos();
    }

    public function cargarCompetencias()
    {
        $this->competencias = DB::table('competencia')
            ->orderBy('com_id', 'desc')
            ->get();
    }

    public function agregarObjetivo()
    {
        $this->objetivos[] = (object) [
            'obj_id' => null,
            'obj_descripcion' => null,
            'com_id' => null,
            'created_at' => now()
        ];
    }

    public function quitarObjetivo($index)
    {
        unset($this->objetivos[$index]);
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
            <h4 class="mb-0">Objetivo</h4>

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
                            <th>Objetivo</th>
                            <th>Competencia</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($objetivos as $objetivo)
                            <tr>
                                <td><input wire:model="objetivos.{{ $loop->index }}.obj_descripcion" class="form-control" type="text" value="{{ $objetivo->obj_descripcion }}"></td>
                                <td>
                                    <select class="form-control" wire:model="objetivos.{{ $loop->index }}.com_id">
                                            <option value="">Seleccione...</option>
                                        @forelse ($competencias as $comp)
                                            <option value="{{ $comp->com_id }}">
                                                {{ $comp->com_descripcion }}
                                            </option>
                                        @empty
                                            <option>Sin competencias</option>
                                        @endforelse

                                    </select>
                                </td>
                                <td>{{ $objetivo->created_at }}</td>
                                <td>
                                    @if(!empty($objetivo->obj_id))
                                        <button
                                        class="btn btn-danger"
                                        wire:click="eliminar({{ $objetivo->obj_id }})"
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