<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class CompetenciaController extends Controller
{
    public function getIndex()
    {
        return view("Modules.competencia.index");
    }
}
