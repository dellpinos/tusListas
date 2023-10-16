<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use App\Mail\InvitarUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class APIOwnerTools extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function all()
    {
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $users = User::orderBy('name', 'asc')->where('empresa_id', session('empresa')->id)->get();

        return json_encode([
            'users' => $users,
            'owner' => auth()->user()
        ]);
    }
    public function name()
    {
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $name_empresa = session('empresa')->name;

        return json_encode($name_empresa);
    }
    public function destroy(Request $request)
    {
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $id = filter_var($request->id, FILTER_VALIDATE_INT);
        $user = User::destroy($id);

        return json_encode($user);
    }
    public function update(Request $request)
    {

         // Mail::to('correo_destino@example.com')->send(new InvitarUsuario()); // <<<< Como enviar emails

        $empresa = session('empresa');

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30|min:3|string|unique:empresas,name,' . $empresa->id
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors()
            ]);
        }

        
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $name = $request->name;
        $empresa->name = $name;
        $empresa->save();

        if (!$empresa) {
            return json_encode([
                'error' => "Algo salió mal."
            ]);
        }

        $empresa = Empresa::find(auth()->user()->empresa_id);

        if (!$empresa) {
            auth()->logout();
            return redirect()->route('login');
        }

        session()->put('empresa', $empresa);

        return json_encode($empresa);
    }
}
