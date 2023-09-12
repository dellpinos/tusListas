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
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();


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


        $this->validate($request, [
            'nombre' => 'required|max:60|unique:fabricantes',
            'telefono' => 'string|nullable|max:60',
            'vendedor' => 'string|nullable|max:60',
            'descripcion' => 'string|nullable|max:500'
        ]);
        // Añadir validaciones y autenticación
        Fabricante::create([
            'nombre' => $request->nombre,
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

        $this->validate($request, [
            'nombre' => 'required|string|max:60|unique:fabricantes,nombre,' . $fabricante->id,
            'telefono' => 'string|nullable|max:60',
            'vendedor' => 'string|nullable|max:60',
            'descripcion' => 'string|nullable|max:500'
        ]);

        $fabricante->nombre = $request->nombre;
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
