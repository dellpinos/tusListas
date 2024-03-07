<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
// use App\Models\Precio;

class IngresoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }
    
    public function index()
    {

        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();

        return view('ingreso.index', [
            "dolar_hoy" => $dolar_hoy
        ]);
    }
}