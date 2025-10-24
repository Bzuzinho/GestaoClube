<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui podes definir as rotas da API do teu projeto.
| Estas rotas são automaticamente associadas ao middleware "api".
|
*/

Route::get('/status', function () {
    return response()->json(['status' => 'API online']);
});
