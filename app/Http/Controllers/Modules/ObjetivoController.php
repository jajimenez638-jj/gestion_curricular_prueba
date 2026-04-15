<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class ObjetivoController extends Controller
{
    public function getIndex()
    {
        return view("Modules.objetivo.index");
    }
}
