<?php

use Livewire\Component;

new class extends Component
{
    public $curso_id;
    public $curso;
    public $programaDetalle;
    public $search = '';
    public $editId = null;
    public $descripcion = '';
    
    public $f_programa = '';
    public $f_competencia = '';
    public $f_objetivo = '';
    public $f_nivel = '';

    public function edit($id)
    {
        $programa = ProgramaAcademico::findOrFail($id);

        $this->editId = $programa->pro_aca_id;
        $this->descripcion = $programa->pro_aca_descripcion;
    }

    public function mount($id)
    {
        $this->curso_id = $id;
        $this->cargarProgramas($id);
    }

    public function updatedSearch()
    {
        $this->cargarProgramas($this->curso_id);
    }

    public function cargarProgramas($id)
    {
        $this->curso = DB::table('curso')
        ->where('cur_id', $id)
        ->get();

    $this->programaDetalle = DB::table('programa_academico as pa')
        ->join('programa_academico_curso as pac', 'pa.pro_aca_id', '=', 'pac.pro_aca_id')
        ->join('curso as c', 'pac.cur_id', '=', 'c.cur_id')
        ->join('curso_objetivo as co', 'c.cur_id', '=', 'co.cur_id')
        ->join('objetivo as o', 'co.obj_id', '=', 'o.obj_id')
        ->join('competencia as comp', 'o.com_id', '=', 'comp.com_id')

        ->where('c.cur_id', $id)

        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('pa.pro_aca_descripcion', 'like', "%{$this->search}%")
                    ->orWhere('c.cur_descripcion', 'like', "%{$this->search}%")
                    ->orWhere('o.obj_descripcion', 'like', "%{$this->search}%")
                    ->orWhere('comp.com_descripcion', 'like', "%{$this->search}%")
                    ->orWhere('co.nivel', 'like', "%{$this->search}%");
            });
        })

        ->when($this->f_programa, fn($q) =>
            $q->where('pa.pro_aca_descripcion', 'like', "%{$this->f_programa}%")
        )

        ->when($this->f_competencia, fn($q) =>
            $q->where('comp.com_descripcion', 'like', "%{$this->f_competencia}%")
        )

        ->when($this->f_objetivo, fn($q) =>
            $q->where('o.obj_descripcion', 'like', "%{$this->f_objetivo}%")
        )

        ->when($this->f_nivel, fn($q) =>
            $q->where('co.nivel', $this->f_nivel)
        )

        ->select(
            'pa.pro_aca_descripcion',

            'c.cur_id',
            'c.cur_descripcion',

            'co.cur_obj_id',
            'co.nivel',

            'o.obj_id',
            'o.obj_descripcion',

            'comp.com_id',
            'comp.com_descripcion'
        )
        ->get();
    }

    public function updated($field)
    {
        $this->cargarProgramas($this->curso_id);
    }
};
?>

<div>
    <div class="container my-4">

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Curso</h4>
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
                                <td>{{ $cur->cur_descripcion }}</td>
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
                            <th><input type="text" class="form-control"
                                        placeholder="Programa"
                                        wire:model.live="f_programa"></th>
                            <th><input type="text" class="form-control"
                                        placeholder="Competencia"
                                        wire:model.live="f_competencia"></th>
                            <th> <input type="text" class="form-control"
                                        placeholder="Objetivo"
                                        wire:model.live="f_objetivo"></th>
                            <th>
                                <select class="form-control" wire:model.live="f_nivel">
                                    <option value="">Todos</option>
                                    <option value="I">I</option>
                                    <option value="F">F</option>
                                    <option value="V">V</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programaDetalle as $proDet)
                            <tr>
                                <td>{{ $proDet->pro_aca_descripcion }}</td>
                                <td>{{ $proDet->com_descripcion }}</td>
                                <td>{{ $proDet->obj_descripcion }}</td>
                                <td>{{ $proDet->nivel }}</td>
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
                            <td colspan="4">
                               <a class="btn btn-success" href="{{ url('curso')}}" wire:navigate>Atrás</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
</div>