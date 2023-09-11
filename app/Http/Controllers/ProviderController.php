<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $providers = Provider::orderBy('nombre', 'asc')->paginate(6);


        return view('provider.index', [
            'providers' => $providers
        ]);
    }
    public function create()
    {
        return view('provider.create');
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'nombre' => 'required|unique:providers|max:60',
            'email' => 'email|nullable',
            'telefono' => 'string|nullable',
            'vendedor' => 'string|max:60|nullable',
            'web' => 'url|nullable',
            'ganancia' => 'required|numeric', 'between:0.01,9.99'
        ]);


        // Añadir validaciones y autenticación
        Provider::create([
            'nombre' => $request->nombre,
            'email' =>$request->email,
            'telefono' => $request->telefono,
            'vendedor' => $request->vendedor,
            'web' => $request->web,
            'ganancia' => $request->ganancia,
            'providersCategorias_id' => 1 // <<<<< Cambiar este dato, solo es una prueba
        ]);

        return redirect()->route('providers');

    }
    public function edit(Provider $provider)
    {
        return view('provider.edit', [
            'provider' => $provider
        ]);

    }
    public function update(Request $request)
    {
        $provider = Provider::find($request->id);

        $this->validate($request, [
            'nombre' => 'required|max:60|unique:providers,nombre,' . $provider->id,
            'email' => 'email|nullable',
            'telefono' => 'string|nullable',
            'vendedor' => 'string|max:60|nullable',
            'web' => 'url|nullable',
            'ganancia' => 'required|numeric', 'between:0.01,9.99'
        ]);


        $provider->nombre = $request->nombre;
        $provider->email = $request->email;
        $provider->telefono = $request->telefono;
        $provider->vendedor = $request->vendedor;
        $provider->web = $request->web;
        $provider->ganancia = $request->ganancia;
        $provider->providersCategorias_id = 1;


        $provider->save();

        return redirect()->route('providers');

    }
    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('providers');
    }
}
