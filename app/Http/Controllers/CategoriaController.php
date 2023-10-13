<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'nombre' => 'required|string|max:60|min:3|unique:categorias,nombre,NULL,id,empresa_id,' . session('empresa')->id, // Ignora los nombres en otras empresas
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'ganancia' => $request->ganancia,
            'empresa_id' => session('empresa')->id
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
        $categoria = Categoria::where('id', $request->id)->where('empresa_id', session('empresa')->id)->first();
        
        $this->validate($request, [

            'nombre' => [
                'required',
                'string',
                'max:60',
                'min:3',
                Rule::unique('categorias', 'nombre')->where(function ($query) {
                    return $query->where('empresa_id', session('empresa')->id); // solo tiene en cuenta la empresa del usuario
                })->ignore($categoria->id), // Ignora el registro actual
            ],
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        $categoria->nombre = $request->nombre;
        $categoria->ganancia = $request->ganancia;

        $categoria->save();

        return redirect()->route('categorias');

    }
}