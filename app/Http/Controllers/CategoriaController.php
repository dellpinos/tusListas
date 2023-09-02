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
            'name' => 'required|unique:categorias|max:60',
            'ganancia' => 'required'
        ]);

        // Añadir validaciones y autenticación // Falta agregar validaciones, por ejemplo el nombre debe ser unique
        Categoria::create([
            'nombre' => $request->name,
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

        $this->validate($request, [
            'nombre' => 'required|unique:categorias|max:60',
            'ganancia' => 'required'
        ]);

        // Falta agregar validaciones, por ejemplo el nombre debe ser unique

        $categoria = Categoria::find($request->id);

        $categoria->nombre = $request->name;
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
