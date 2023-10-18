<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Str;

class APICodigo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function generar_codigo()
    {

        // Generar código al azar y verificar si existe
        do {
            $codigo = Str::lower(Str::random(4));
        } while (Producto::where('codigo', $codigo)->exists()); // Verifina que sea unico o vuelve a generar

        echo json_encode($codigo);

    }
}
