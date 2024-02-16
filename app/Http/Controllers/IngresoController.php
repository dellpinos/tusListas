<?php

namespace App\Http\Controllers;

use App\Models\Precio;

class IngresoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }
    
    public function index()
    {
        // Dolar mas alto registrado en la DB
        $precio = Precio::orderBy('dolar', 'desc')->where('empresa_id', session('empresa')->id)->first();
        $dolar = $precio->dolar ?? 1;

        return view('ingreso.index', [
            'dolar' => $dolar
        ]);
    }
}