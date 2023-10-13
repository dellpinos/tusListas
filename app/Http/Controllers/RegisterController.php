<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function index()
    {
        return view('empresa.register');
    }
    public function store(Request $request)
    {

        $request->request->add([
            'username' => Str::slug($request->username)
        ]);

        // Validar formulario
        $this->validate($request, [
            'name' => 'required|max:30|min:3|unique:empresas|string',
            'usuario' => 'required|max:30|min:3|string',
            'username' => ["required", "unique:users", "min:3", "max:20", "not_in:logout,register"],
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6|max:32',
        ]);

        $resultado = Empresa::create([
            'name' => $request->name,
            'plan' => 'free' ///// <<<<<<<<<<<<  Este default debe cambiar para restringir el acceso a usuarios vip
        ]);

        if ($resultado) {

            // Crear registro
            User::create([
                'name' => $request->usuario,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'empresa_id' => $resultado->id,
                'user_type' => 'owner'

            ]);
        }

        // Redireccionar
        return redirect()->route('login');
    }
}
