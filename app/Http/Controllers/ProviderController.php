<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('provider.index');
    }
    public function create()
    {
        return view('provider.create');
    }
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'nombre' => 'required|max:60|min:3|unique:providers,nombre,NULL,id,empresa_id,' . session('empresa')->id, // Ignora los nombres en otras empresas
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
            'ganancia' => $request->ganancia,
            'empresa_id' => session('empresa')->id
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

        $provider = Provider::where('id', $request->id)->where('empresa_id', session('empresa')->id)->first();

        $this->validate($request, [

            'nombre' => [
                'required',
                'max:60',
                'min:3',
                Rule::unique('providers', 'nombre')->where(function ($query) {
                    return $query->where('empresa_id', session('empresa')->id); // solo tiene en cuenta la empresa del usuario
                })->ignore($provider->id), // Ignora el registro actual
            ],
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
}
