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

// Recibe 1 precio y 1 producto, retorna un array con 1 precio, 1 producto, 1 categoria y 1 provider
// Consulta y retorna el provider y la categoria del producto
// Identifica la ganancia aplicada al producto en base a provider, categoria o producto
// Sobreescribe el timestamp del precio (Hora - 03 UTH)
// Calcula y añade precio de venta a producto (+ 21% IVA, + indice ganancia - descuento temporal)
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

    $precio_costo = descuentoTemporal($precio);

    $precio->semanas_restantes = duracionDescuento($precio);
    $producto->venta = $producto->ganancia * ($precio_costo * 1.21);

    // Producto fraccionado
    if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {
        $producto->venta = $producto->ganancia * ($precio_costo * 1.21);
        $producto->venta = ($producto->venta * $producto->ganancia_fraccion) / $producto->contenido_total;
        $producto->venta = redondear($producto->venta);
    }

    $resultado = [
        'producto' => $producto,
        'precio' => $precio,
        'categoria' => $categoria,
        'provider' => $provider
    ];

    return $resultado;
}

// Descuentos Temporales
// Recibe una instancia de Precio
// Retorna un precio de costo
// Evalua un precio, si este precio tiene un descuento calcula la fecha del mismo con relacion a la fecha actual
// Si esta diferencia supera la duracion del descuento elimina estas columnas de "precios"
// Si esta fecha no supera la duracion, aplica el descuento al precio (no modifica la DB en este caso)

function descuentoTemporal(Precio $precio)
{

    $precio_costo = $precio->precio;

    if ($precio->desc_porc) {
        // Tiene un descuento
        $semanas_restantes = duracionDescuento($precio);

        if ($semanas_restantes < 0) {
            // eliminar descuento
            $precio->desc_duracion = 0;
            $precio->desc_porc = null;
            $precio->updated_at = now();
            $precio->save();
        } else {
            // aplicar descuento
            $precio_costo = $precio->precio * (1 - $precio->desc_porc / 100);
        }
    }

    return $precio_costo;
}

// Recibe un Precio y retorna un int (cuantas semanas restan de descuento)
function duracionDescuento(Precio $precio)
{
    if ($precio->desc_porc) {

        $fecha_actual = now();
        $fecha_descuento = $precio->updated_at;
        $duracion_descuento = $precio->desc_duracion;
        $diferencia_semanas = $fecha_descuento->diffInWeeks($fecha_actual);
        $semanas_restantes = $duracion_descuento - $diferencia_semanas;

        return $semanas_restantes;
    } else {
        return false;
    }
}
