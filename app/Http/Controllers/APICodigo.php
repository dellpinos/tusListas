<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class APICodigo extends Controller
{
    public function generar_codigo()
    {
        // Generar código al azar y verificar si existe

        do {
            $codigo = Str::lower(Str::random(4));
        } while (Producto::where('codigo', $codigo)->exists()); // Verifina que sea unico o vuelve a generar

        echo json_encode($codigo);


    }
}
