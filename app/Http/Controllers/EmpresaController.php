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

    public function index ()
    {
        // Evalua el rol del usuario
        if(auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin'){
            return redirect(route('buscador'));
        }

        return view('empresa.owner-tools');
    }
}
