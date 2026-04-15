<?php

use Livewire\Component;

new class extends Component
{
    public $search = '';
    public $curso = [];
    public $objetivos = [];
    public $curso_objetivo = [];
    public $curso_id;

    public function mount($id)
    {
        $this->cargarCursoDetalle($id);
        $this->cargarObjetivos();
    }

    public function updatedSearch($id)
    {
        $this->cargarCursoDetalle();
    }

    public function cargarCursoDetalle($id)
    {
        $this->curso_id = $id;
        $this->curso = DB::table('curso')
            ->where('curso.cur_id', $id)
            ->orderBy('curso.cur_id', 'desc')
            ->get();

        $this->curso_objetivo = DB::table('curso_objetivo')
            ->where('cur_id', $id)
            ->orderBy('cur_id', 'desc')
            ->get()
            ->toArray();
    }

    public function guardarCambios()
    {
        DB::transaction(function () {
            foreach ($this->curso as $cur) {
                if(trim($cur->cur_descripcion) == ""){
                    continue;
                }
                DB::table('curso')
                    ->where('cur_id', $cur->cur_id)
                    ->update([
                        'cur_descripcion' => $cur->cur_descripcion
                    ]);

                foreach ($this->curso_objetivo as $relacion) {
                    if(trim($relacion->obj_id) == "" || trim($relacion->nivel) == ""){
                        continue;
                    }
                    if (!empty($relacion->cur_obj_id)) {

                        DB::table('curso_objetivo')
                            ->where('cur_obj_id', $relacion->cur_obj_id)
                            ->update([
                                'obj_id' => $relacion->obj_id,
                                'nivel' => $relacion->nivel,
                            ]);
                    }else {

                        DB::table('curso_objetivo')->insert([
                            'cur_id' => $this->curso_id,
                            'obj_id' => $relacion->obj_id,
                            'nivel' => $relacion->nivel,
                            'created_at' => now(),
                        ]);
                    }
                }
            }
        });

        session()->flash('success', 'Actualización exitosa');

        $this->cargarCursoDetalle($this->curso_id);
    }

    public function eliminar($id)
    {
        DB::table('curso_objetivo')
            ->where('cur_obj_id', $id)
            ->delete();

        session()->flash('success', 'Curso eliminado correctamente');

        $this->cargarCursoDetalle($this->curso_id);
    }

    public function cargarObjetivos()
    {
        $this->objetivos = DB::table('objetivo')->orderBy('obj_id', 'desc')->get();
    }

    public function agregarObjetivo()
    {
        $this->curso_objetivo[] = (object) [
            'cur_obj_id' => null,
            'cur_id' => $this->curso_id,
            'obj_id' => '',
            'nivel' => null,
        ];
    }
    
    public function quitarObjetivo($index)
    {
        unset($this->curso_objetivo[$index]);
        // $this->curso_objetivo = array_values($this->curso_objetivo);
    }

    protected function rules()
    {
        return [
            'curso.0.cur_descripcion' => 'required|string|max:255',

            'curso_objetivo.*.obj_id' => 'required',
            'curso_objetivo.*.nivel' => 'required',
        ];
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
            <h4 class="mb-0">Curso detalle</h4>

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
                            <th>Curso</th>
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curso as $cur)
                            <tr>
                                <td>{{ $cur->cur_id }}</td>
                                <td><input class="form-control" type="text" wire:model="curso.0.cur_descripcion"></td>
                                <td>{{ $cur->created_at }}</td>
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

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Objetivos</th>
                            <th>Nivel</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curso_objetivo as $curObj)
                            <tr>
                                <td>
                                    <select class="form-control" wire:model="curso_objetivo.{{ $loop->index }}.obj_id">
                                            <option value=''>Seleccione...</option>
                                        @forelse ($objetivos as $obj)
                                            <option value="{{ $obj->obj_id }}">
                                                {{ $obj->obj_descripcion }}
                                            </option>
                                        @empty
                                            <option>Sin competencias</option>
                                        @endforelse
                                    </select>
                                    @error('curso_objetivo.' . $loop->index . '.obj_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <select class="form-control" wire:model="curso_objetivo.{{ $loop->index }}.nivel">
                                        <option value="">Seleccione...</option>
                                        <option value="I">Inicial</option>
                                        <option value="F">Formativo</option>
                                        <option value="V">Validado</option>
                                    </select>
                                    @error('curso_objetivo.' . $loop->index . '.nivel')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    @if(!empty($curObj->cur_obj_id))
                                        <button
                                        class="btn btn-danger"
                                        wire:click="eliminar({{ $curObj->cur_obj_id }})"
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
                                <td colspan="3" class="text-center text-muted">
                                    No hay resultados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                               <a class="btn btn-success" href="{{ url('curso')}}" wire:navigate>Atrás</a>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-success" wire:click="guardarCambios">Guardar cambios</a>
                            </td>
                            <td class="text-end">
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