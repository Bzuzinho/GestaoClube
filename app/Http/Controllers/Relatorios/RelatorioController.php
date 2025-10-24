<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;

class RelatorioController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }
}
