<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class CursoController extends Controller
{
    public function getIndex()
    {
        return view("Modules.curso.index");
    }
}
