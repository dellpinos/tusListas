<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return redirect(route('buscador'));
        }

        $users = User::orderBy('name', 'asc')->where('empresa_id', session('empresa')->id)->count();

        return view('empresa.owner-tools', [
            'contador_users' => $users
        ]);
    }
}
