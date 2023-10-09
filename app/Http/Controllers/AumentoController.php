<?php

namespace App\Http\Controllers;

use App\Models\Aumento;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;

class AumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $providers = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();

        return view('aumentos.index', [
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'providers' => $providers
        ]);
    }
    public function listado_aumentos()
    {
        $registros = Aumento::all();

        return view('aumentos.listado', [
            'registros' => $registros
        ]);
    }

    public function dolar_aumentos()
    {
        return view('aumentos.dolar');
    }
}