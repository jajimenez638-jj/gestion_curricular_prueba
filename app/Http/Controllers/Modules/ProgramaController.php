<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class ProgramaController extends Controller
{
    public function getIndex()
    {
        return view("Modules.programa.index");
    }
}
