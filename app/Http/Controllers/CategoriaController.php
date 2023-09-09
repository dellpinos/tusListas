<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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

        $this->validate($request, [
            'nombre' => 'required|unique:categorias|max:60',
            'ganancia' => 'required|numeric', 'between:0.01,9.99'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'ganancia' => $request->ganancia,
            'providersCategorias_id' => 1 // <<<<< Cambiar este dato, solo es una prueba
        ]);

        return redirect()->route('categorias');
    }



    public function edit(Categoria $categoria)
    {
        return view('categoria.edit', [
            'categoria' => $categoria
        ]);

    }
    public function update(Request $request)
    {
        $categoria = Categoria::find($request->id);
        
        $this->validate($request, [
            'nombre' => 'required|max:60|unique:categorias,nombre,' . $categoria->id,
            'ganancia' => 'required|numeric', 'between:0.01,9.99'
        ]);

        // Falta agregar validaciones, por ejemplo el nombre debe ser unique


        $categoria->nombre = $request->nombre;
        $categoria->ganancia = $request->ganancia;
        $categoria->providersCategorias_id = 1; // <<<<< Cambiar este dato, solo es una prueba


        $categoria->save();

        return redirect()->route('categorias');

    }
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()->route('categorias');
    }
}
