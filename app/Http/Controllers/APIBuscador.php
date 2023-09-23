<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIBuscador extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
    }
    public function nombre_producto(Request $request)
    {

        // Busqueda segun coincidencia de 3 caracteres
        $patron = $request->input('input_producto');

        if ($request->filtro_frac) {
            // No retorna fraccionados, esto es util en buscadores que modifican registros
            $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->whereNull('ganancia_fraccion')->get();
        } else {

            $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->get();
        }

        echo json_encode($resultado);
    }
    public function producto_individual(Request $request)
    {
        $id = $request->input('id');

        $producto = Producto::find($id);
        $precio = Precio::find($producto->precio_id);

        // Calcular precio de venta
        $resultado = precioVenta($producto, $precio);


        echo json_encode($resultado);
    }

    public function codigo_producto(Request $request)
    {
        // Filtra fraccionados
        if ($request->filtro_frac) {
            // No retorna fraccionados, esto es util en buscadores que modifican registros
            $resultado = Producto::where('codigo', $request->codigo_producto)->whereNull('ganancia_fraccion')->get();
            
        } else {

            $resultado = Producto::where('codigo', $request->codigo_producto)->get();
        }
        
        if ($resultado->isEmpty()) {
            echo json_encode(false);
            return;
        }
        echo json_encode($resultado);
    }
}
