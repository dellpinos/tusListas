<?php

namespace App\Http\Controllers;

use App\Models\Aumento;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class AumentoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $proveedores = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();

        return view('aumentos.index', [
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'proveedores' => $proveedores
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

/// Crear vista para registro de aumentos




