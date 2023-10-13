<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!auth()->attempt($request->only('email', 'password'), $request->remember)) {
            return back()->with('mensaje', 'Email o password incorrecto');
        }

        $empresa = Empresa::find(auth()->user()->empresa_id);

        if(!$empresa) {
            auth()->logout();
            return redirect()->route('login');
        }

        session()->put('empresa', $empresa);

        return redirect()->route('buscador');
    }
}
