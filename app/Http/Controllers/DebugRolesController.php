<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class DebugRolesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles_do_utilizador' => $user->getRoleNames(),
            'todos_os_roles_existentes' => Role::all()->pluck('name'),
        ]);
    }
}
