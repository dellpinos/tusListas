<?php

use App\Models\Producto;
use Illuminate\Support\Str;



// Redondea al numero mayor y multiplo de cero mas cercano
function redondear($numero)
{
    return ceil($numero / 10) * 10;
}

// Generar código al azar y verificar si existe
function generarCodigo()
{
    do {
        $codigo = Str::lower(Str::random(4));
    } while (Producto::where('codigo', $codigo)->exists()); // Verifina que sea unico o vuelve a generar
    return $codigo;
}

// Debuguear
function debuguear($elemento) {
    echo "<pre>";
        var_dump($elemento);
    echo "</pre>";
    die();
}
