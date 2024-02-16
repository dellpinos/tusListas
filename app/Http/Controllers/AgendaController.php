<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class AgendaController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }

    public function index()
    {
        // Contar cantidad de categorias, proveedores y fabricantes

        $categorias = Categoria::where('empresa_id', session('empresa')->id)->count();
        $providers = Provider::where('empresa_id', session('empresa')->id)->count();
        $fabricantes = Fabricante::where('empresa_id', session('empresa')->id)->count();
        $productos = Producto::where('empresa_id', session('empresa')->id)->count();


        return view('agenda.index', [
            'contador_categorias' => $categorias,
            'contador_providers' => $providers,
            'contador_fabricantes' => $fabricantes,
            'contador_productos' => $productos
        ]);
    }
}
