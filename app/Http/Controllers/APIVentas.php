<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class APIVentas extends Controller
{
    public function index()
    {
        $ventas = Venta::all();

        echo json_encode([
            'ventas' => $ventas
        ]);
    }
    public function nueva_venta(Request $request)
    {

        // Dolar Actual
        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();
        $producto = Producto::find($request->id);
        
        // Venta sin descuento
        $calculo_ganancia = 0;
        $total = 0;
        $total_dolar = 0;
        $ganancia = 1;
        
        // Tipo de ganancia
        if ($producto->ganancia_tipo === 'provider') {
            $ganancia = $producto->provider->ganancia;
        } else if ($producto->ganancia_tipo === 'categoria') {
            $ganancia = $producto->categoria->ganancia;
        } else if ($producto->ganancia_tipo === 'producto') {
            $ganancia = $producto->ganancia_prod;
        }

        $venta = ($producto->precio->precio * 1.21) * $ganancia;
        
        // Calcular ingreso en dolares
        if (!$producto->precio->desc_porc) {
            // No tiene descuento
            $calculo_ganancia = $venta - ($producto->precio->precio * 1.21);
            $total = $calculo_ganancia * $request->cantidad_vendida;
            $total_dolar = $total / $dolar_hoy->valor;

        } else {
            // Tiene descuento - Aplicar descuento
            $venta_descuento = $venta - ($venta * ($producto->precio->desc_porc / 100));
            $calculo_ganancia = $venta_descuento - ($producto->precio->precio * 1.21);
            $total = $calculo_ganancia * $request->cantidad_vendida;
            $total_dolar = $total / $dolar_hoy->valor;
            
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
            'monto' => $total,
            'monto_dolar' => $total_dolar
        ]);

        echo json_encode([
            'respuesta' => $respuesta
        ]);
    }
}
