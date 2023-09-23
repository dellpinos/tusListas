<?php

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
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
function debuguear($elemento)
{
    echo "<pre>";
    var_dump($elemento);
    echo "</pre>";
    die();
}

// Recibe 1 precio y 1 producto, retorna un array con 1 precio y 1 producto
// Identifica la ganancia aplicada al producto en base a provider, categoria o producto
// Sobreescribe el timestamp del precio (Hora - 03 UTH)
// Calcula y añade precio de venta a producto (+ 21% IVA, + indice ganancia - descuento temporal)
// Incrementa Producto "veces mostrado"
// Consulta-Elimina descuento temporal
// Redondea el precio de venta
function precioVenta(Producto $producto, Precio $precio)
{

    $precio->updated_at = $precio->updated_at->subHours(3);

    $categoria = Categoria::find($producto->categoria_id);
    $provider = Provider::find($producto->provider_id);

    // Que ganancia aplica a este producto
    if (!$producto->ganancia_prod) {
        if ($producto->ganancia_tipo === 'provider') {
            $producto->ganancia = $provider->ganancia;
            $producto->ganancia_tipo = 'Proveedor';
        } else {
            $producto->ganancia = $categoria->ganancia;
            $producto->ganancia_tipo = 'Categoria';
        }
    } else {
        $producto->ganancia = $producto->ganancia_prod;
        $producto->ganancia_tipo = 'Producto';
    }

    $producto->venta = $producto->ganancia * ($precio->precio * 1.21);

    // Producto fraccionado
    if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {
        $producto->venta = $producto->ganancia * ($precio->precio * 1.21);
        $producto->venta = ($producto->venta * $producto->ganancia_fraccion) / $producto->contenido_total;
        $producto->venta = redondear($producto->venta);
    }

    $resultado = [
        'producto' => $producto,
        'precio' => $precio
    ];

    return $resultado;
}
