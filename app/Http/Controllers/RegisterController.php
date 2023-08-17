<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    public function store(Request $request)
    {
        // Sanitizar username
        $request->request->add([
            'username' => Str::slug($request->username)
        ]);

        // Validar formulario
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => ["required", "unique:users", "min:3", "max:20", "not_in:logout,register"],
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6',
        ]);

        // Crear registro
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Autenticar al usuario
        auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Redireccionar
        // return redirect()->route('buscador.index');
    }
}
