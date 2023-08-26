<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre', 'asc')->paginate(6);


        return view('categoria.index', [
            'categorias' => $categorias
        ]);
    }
    public function create()
    {
        return view('categoria.create');
    }
    public function store(Request $request)
    {

        // Añadir validaciones y autenticación
        Categoria::create([
            'nombre' => $request->name,
            'ganancia' => $request->ganancia,
            'providersCategorias_id' => 1 // <<<<< Cambiar este dato, solo es una prueba
        ]);

        return redirect()->route('categorias');

    }
}
