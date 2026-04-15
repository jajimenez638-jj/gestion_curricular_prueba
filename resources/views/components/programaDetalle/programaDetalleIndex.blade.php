<?php

use Livewire\Component;

new class extends Component
{
    public $programa;
    public $programaDetalle;
    public $search = '';
    public $editId = null;
    public $descripcion = '';
    
    public function edit($id)
    {
        $programa = ProgramaAcademico::findOrFail($id);

        $this->editId = $programa->pro_aca_id;
        $this->descripcion = $programa->pro_aca_descripcion;
    }

    public function mount($id)
    {
        $this->cargarProgramas($id);
    }

    public function updatedSearch()
    {
        $this->cargarProgramas();
    }

    public function cargarProgramas($id)
    {
        $this->programa = DB::table('programa_academico')
            ->where('pro_aca_id', $id)
            ->get();

        $this->programaDetalle = DB::table('programa_academico as pa')
            ->join('curso as c', 'pa.pro_aca_id', '=', 'c.pro_aca_id')
            ->join('curso_objetivo as co', 'c.cur_id', '=', 'co.cur_id')
            ->join('objetivo as o', 'co.obj_id', '=', 'o.obj_id')
            ->join('competencia as comp', 'o.com_id', '=', 'comp.com_id')
            ->where('pa.pro_aca_id', $id)
            ->select(
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
};
?>

<div>
    <div class="container my-4">

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Programas Académicos Detalle</h4>

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
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programa as $proAca)
                            <tr>
                                <td>{{ $proAca->pro_aca_id }}</td>
                                <td>{{ $proAca->pro_aca_descripcion }}</td>
                                <td>{{ $proAca->created_at }}</td>
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
                            <th>Curso</th>
                            <th>Competencia</th>
                            <th>Objetivo</th>
                            <th>Nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programaDetalle as $proDet)
                            <tr>
                                <td>{{ $proDet->cur_descripcion }}</td>
                                <td>{{ $proDet->com_descripcion }}</td>
                                <td>{{ $proDet->obj_descripcion }}</td>
                                <td>{{ $proDet->nivel }}</td>
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