<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;


class ProgramadetalleController extends Controller
{
    public function getIndex($id)
    {
        // die($id);
        return view("Modules.programaDetalle.index", compact('id'));
    }
}
