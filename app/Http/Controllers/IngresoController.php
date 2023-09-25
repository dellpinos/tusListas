<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // Dolar mas alto registrado en la DB
        $precio = Precio::orderBy('dolar', 'desc')->first();
        $dolar = $precio->dolar ?? 1;

        return view('ingreso.index', [
            'dolar' => $dolar
        ]);
    }
}