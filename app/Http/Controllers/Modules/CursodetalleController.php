<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class CursodetalleController extends Controller
{
    public function getIndex($id)
    {
        return view("Modules.cursoDetalle.index", compact('id'));
    }
    public function show($id)
    {
        return view("Modules.cursoDetalle.show", compact('id'));
    }
}
