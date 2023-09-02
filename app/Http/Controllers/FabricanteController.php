<?php

namespace App\Http\Controllers;

use App\Models\Fabricante;
use Illuminate\Http\Request;

class FabricanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->paginate(6);


        return view('fabricante.index', [
            'fabricantes' => $fabricantes
        ]);
    }
    public function create()
    {
        return view('fabricante.create');
    }
    public function store(Request $request)
    {
        // Añadir validaciones y autenticación
        Fabricante::create([
            'nombre' => $request->name,
            'telefono' => $request->telefono,
            'vendedor' => $request->vendedor,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('fabricantes');

    }
    public function edit(Fabricante $fabricante)
    {
        return view('fabricante.edit', [
            'fabricante' => $fabricante
        ]);

    }
    public function update(Request $request)
    {

        $fabricante = Fabricante::find($request->id);

        $fabricante->nombre = $request->name;
        $fabricante->telefono = $request->telefono;
        $fabricante->vendedor = $request->vendedor;
        $fabricante->descripcion = $request->descripcion;

        $fabricante->save();

        return redirect()->route('fabricantes');

    }
    public function destroy(Fabricante $fabricante)
    {
        $fabricante->delete();

        return redirect()->route('fabricantes');
    }

}
