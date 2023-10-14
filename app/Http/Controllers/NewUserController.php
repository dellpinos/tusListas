<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class NewUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

    }
    public function index()
    {

        if (auth()->user()->user_type !== 'admin') {
            return redirect()->route('login');
        }

        return view('auth.register');
    }
    public function store(Request $request)
    {

        $request->request->add([
            'username' => Str::slug($request->username)
        ]);

        // Validar formulario
        $this->validate($request, [
            'name' => 'required|max:30|string|min:3',
            'username' => ["required", "unique:users", "min:3", "max:20", "not_in:logout,register"],
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6|max:60',
        ]);

        // Crear registro
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'empresa_id' => session('empresa')->id,
            'user_type' => 'user'
        ]);

        // Redireccionar
        return redirect()->route('buscador');
    }
}
