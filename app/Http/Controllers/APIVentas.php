<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class APIVentas extends Controller
{
    public function nueva_venta(Request $request)
    {

        // estoy almacenando el precio de costo, debo almacenar la ganancia

        // Dolar Actual
        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();
        $producto = Producto::find($request->id);

        $ganancia = 1;
        if($producto->ganancia_tipo === 'provider') {
            $ganancia = $producto->provider->ganancia;

        } else if($producto->ganancia_tipo === 'categoria') {
            $ganancia = $producto->categoria->ganancia;

        } else if($producto->ganancia_tipo === 'producto') {
            $ganancia = $producto->ganancia_prod;
        }

        $calculo_ganancia = (($producto->precio->precio * 1.21) * $ganancia) - ($producto->precio->precio * 1.21);

        // Calcular ingreso en dolares
        if (!$producto->precio->desc_porc) {
            // No tiene descuento
            $total_dolar = ($calculo_ganancia * $request->cantidad_vendida) / $dolar_hoy->valor;
        } else {
            // Tiene descuento
            $total_dolar = (($calculo_ganancia * ($producto->precio->desc_porc / 100)) * $request->cantidad_vendida) / $dolar_hoy->valor;
        }

        // No se resta el stock de productos fraccionados
        if (!$producto->unidad_fraccion) {
            // Actualizar stock del producto
            $producto->stock = $request->stock_restante;
            $producto->save();
        }

        $respuesta = Venta::create([
            'empresa_id' => session('empresa')->id,
            'producto_id' => $request->id,
            'monto_dolar' => $total_dolar
        ]);

        echo json_encode([
            'respuesta' => $respuesta
        ]);
    }
}
