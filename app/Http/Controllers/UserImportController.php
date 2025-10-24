<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserImportController extends Controller
{
    public function index()
    {
        // View mínima só para não partir as rotas
        return view('pessoas.importacao');
    }

    public function store(Request $request)
    {
        // TODO: validação + import real
        return back()->with('success', 'Importação iniciada (stub).');
    }
}
