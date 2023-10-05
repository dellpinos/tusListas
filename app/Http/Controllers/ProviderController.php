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
        $providers = Provider::orderBy('nombre', 'asc')->get();

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
            'nombre' => 'required|unique:providers|max:60|min:3',
            'email' => 'email|nullable',
            'telefono' => ['string', 'nullable', 'max:20', 'min:5', 'regex:/^[0-9 -]*$/'],
            'vendedor' => 'string|max:60|min:3|nullable',
            'web' => 'string|nullable|url|max:100|min:6',
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        Provider::create([
            'nombre' => $request->nombre,
            'email' =>$request->email,
            'telefono' => $request->telefono,
            'vendedor' => $request->vendedor,
            'web' => $request->web,
            'ganancia' => $request->ganancia
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
            'nombre' => 'required|max:60|min:3|unique:providers,nombre,' . $provider->id,
            'email' => 'email|nullable',
            'telefono' => ['string', 'nullable', 'max:20', 'min:5', 'regex:/^[0-9 -]*$/'],
            'vendedor' => 'string|max:60|min:3|nullable',
            'web' => 'string|nullable|url|max:100|min:6',
            'ganancia' => 'required|numeric|between:1,19.99'
        ]);

        $provider->nombre = $request->nombre;
        $provider->email = $request->email;
        $provider->telefono = $request->telefono;
        $provider->vendedor = $request->vendedor;
        $provider->web = $request->web;
        $provider->ganancia = $request->ganancia;

        $provider->save();

        return redirect()->route('providers');

    }
    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('providers');
    }
}
