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

        return view('categoria.index');
    }
    public function create()
    {
        return view('categoria.create');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'nombre' => 'required|unique:categorias|max:60|min:3',
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'ganancia' => $request->ganancia
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
            'nombre' => 'required|max:60|min:3|unique:categorias,nombre,' . $categoria->id,
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        $categoria->nombre = $request->nombre;
        $categoria->ganancia = $request->ganancia;

        $categoria->save();

        return redirect()->route('categorias');

    }
}