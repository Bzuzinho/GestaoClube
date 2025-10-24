<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;

class InventarioController extends Controller
{
    public function index()
    {
        return view('inventario.index'); // cria a view quando for a altura
    }
}
