<?php

namespace App\Http\Controllers;

use App\Models\Fabricante;
use Illuminate\Http\Request;

class FabricanteController extends Controller
{
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

}
