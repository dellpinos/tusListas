<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Aumento;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;

class AumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $categorias = Categoria::orderBy('nombre', 'asc')->where('empresa_id', session('empresa')->id)->get();
        $providers = Provider::orderBy('nombre', 'asc')->where('empresa_id', session('empresa')->id)->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->where('empresa_id', session('empresa')->id)->get();

        return view('aumentos.index', [
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'providers' => $providers
        ]);
    }
    public function listado_aumentos()
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }


        // Se listan los últimos 50 registros
        $registros = Aumento::orderBy('created_at', 'desc')->where('empresa_id', session('empresa')->id)->take(50)->get();

        return view('aumentos.listado', [
            'registros' => $registros
        ]);
    }

    public function dolar_aumentos()
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        $precios = Precio::orderBy('dolar', 'asc')->where('empresa_id', session('empresa')->id)->count();

        return view('aumentos.dolar', [
            'contador_precios' => $precios
        ]);
    }
}
