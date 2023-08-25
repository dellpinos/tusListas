<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $proveedores = Provider::orderBy('nombre', 'asc')->paginate(6);


        return view('provider.index', [
            'proveedores' => $proveedores
        ]);
    }
    public function create()
    {
        return view('provider.create');
    }
    public function store(Request $request)
    {

        // Añadir validaciones y autenticación
        Provider::create([
            'nombre' => $request->name,
            'email' =>$request->email,
            'telefono' => $request->telefono,
            'vendedor' => $request->vendedor,
            'web' => $request->web,
            'ganancia' => $request->ganancia,
            'providersCategorias_id' => 1 // <<<<< Cambiar este dato, solo es una prueba
        ]);

        return redirect()->route('proveedores');

    }
}
