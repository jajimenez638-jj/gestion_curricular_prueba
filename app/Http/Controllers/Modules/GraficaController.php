<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class GraficaController extends Controller
{
    public function getIndex()
    {
        return view("Modules.grafica.index");
    }
}
