<?php

use Livewire\Component;

new class extends Component
{
    public $search = '';
    public $cursos = [];

    public function mount()
    {
        $this->cargarcurso();
    }

    public function updatedSearch()
    {
        $this->cargarcurso();
    }

    public function cargarcurso()
    {
        $this->cursos = DB::table('curso')
            ->where('cur_descripcion', 'like', '%' . $this->search . '%')
            ->orderBy('cur_id', 'desc')
            ->get();
    }

    public function guardarCambios()
    {
        foreach ($this->cursos as $curso) {
            if(trim($curso->cur_descripcion) == ""){
                continue;
            }

            if (!empty($curso->cur_id)) {

                DB::table('curso')
                    ->where('cur_id', $curso->cur_id)
                    ->update([
                        'cur_descripcion' => $curso->cur_descripcion
                    ]);
            }else {
                $cursoId = DB::table('curso')->insertGetId([
                    'cur_descripcion' => $curso->cur_descripcion,
                    'created_at' => now(),
                ]);
            }

            if (!empty($cursoId)) {
                DB::table('programa_academico_curso')->updateOrInsert(
                    [
                        'pro_aca_id' => 1, // Por defecto Ingeniueria en sistemas
                        'cur_id' => $cursoId
                    ],
                    []
                );
            }
        }

        session()->flash('success', 'Todos los cambios fueron guardados correctamente');

        $this->cargarcurso();
    }

    public function eliminar($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('curso_objetivo')
                ->where('cur_id', $id)
                ->delete();

            DB::table('curso')
                ->where('cur_id', $id)
                ->delete();
        });

        session()->flash('success', 'Curso eliminado correctamente');

        $this->cargarcurso();
    }

    public function agregarObjetivo()
    {
        $this->cursos[] = (object) [
            'cur_id' => null,
            'cur_descripcion' => null
        ];
    }

    public function quitarObjetivo($index)
    {
        unset($this->cursos[$index]);
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
            <h4 class="mb-0">Cursos</h4>

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
                            <th>Curso</th>
                            <th colspan='3'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cursos as $curso)
                            <tr>
                                <td><input wire:model="cursos.{{ $loop->index }}.cur_descripcion" class="form-control" type="text" value="{{ $curso->cur_descripcion }}"></td>
                                <td><a href="{{ url('/cursoDetalleShow/' . $curso->cur_id) }}" class="btn btn-primary" wire:navigate>Ver</a></td>
                                <td><a href="{{ url('/cursoDetalle/' . $curso->cur_id) }}" class="btn btn-success" wire:navigate>Editar</a></td>
                                <td>
                                    @if(!empty($curso->cur_id))
                                        <button
                                        class="btn btn-danger"
                                        wire:click="eliminar({{ $curso->cur_id }})"
                                        wire:confirm="¿Seguro que deseas eliminar este curso?">Eliminar</button>
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
                            <td colspan="3" class="text-center text-muted">
                                <a class="btn btn-success" wire:click="guardarCambios">Guardar cambios</a>
                            </td>
                            <td colspan="1" class="text-center text-muted">
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