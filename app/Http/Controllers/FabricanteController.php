<?php

namespace App\Http\Controllers;

use App\Models\Fabricante;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FabricanteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }
    public function index()
    {
        return view('fabricante.index');
    }
    public function create()
    {
        return view('fabricante.create');
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'nombre' => 'required|string|max:60|min:3|unique:fabricantes,nombre,NULL,id,empresa_id,' . session('empresa')->id, // Ignora los nombres en otras empresas
            'telefono' => ['string', 'nullable', 'max:20', 'min:5', 'regex:/^[0-9 -]*$/'],
            'vendedor' => 'string|nullable|max:60|min:3',
            'descripcion' => 'string|nullable|max:255'
        ]);
        // Añadir validaciones y autenticación
        Fabricante::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'vendedor' => $request->vendedor,
            'descripcion' => $request->descripcion,
            'empresa_id' => session('empresa')->id
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

        $fabricante = Fabricante::where('id', $request->id)->where('empresa_id', session('empresa')->id)->first();

        $this->validate($request, [
            'nombre' => [
                'required',
                'max:60',
                'string',
                'min:3',
                Rule::unique('fabricantes', 'nombre')->where(function ($query) {
                    return $query->where('empresa_id', session('empresa')->id); // solo tiene en cuenta la empresa del usuario
                })->ignore($fabricante->id), // Ignora el registro actual
            ],
            'telefono' => ['string', 'nullable', 'max:20', 'min:5', 'regex:/^[0-9 -]*$/'],
            'vendedor' => 'string|nullable|max:60|min:3',
            'descripcion' => 'string|nullable|max:255'
        ]);

        $fabricante->nombre = $request->nombre;
        $fabricante->telefono = $request->telefono;
        $fabricante->vendedor = $request->vendedor;
        $fabricante->descripcion = $request->descripcion;

        $fabricante->save();

        return redirect()->route('fabricantes');
    }
}
