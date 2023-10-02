<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Paginacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIBuscador extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        // Listar todos los productos paginados
        $registros_por_pagina = 15;

        $pagina_actual = $request->page;

        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $total_registros = Producto::all()->count();

        if (!$pagina_actual || $pagina_actual < 1) {
            return json_encode("error");
        }

        if($total_registros < 1) {
            echo json_encode([
                'productos' => [],
                'precios' => []
            ]);
            return;
        }

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros); // creo la instancia con "la forma de una paginacion"

        if ($paginacion->totalPaginas() < $pagina_actual) {
            return json_encode("error");
        }

        $productos = Producto::orderBy('nombre', 'ASC')->offset($paginacion->offset())->limit($registros_por_pagina)->get(); // ->get()?

        $precios = [];
        $resultado = [];
        
        foreach($productos as $producto) {

            $precio = Precio::find($producto->precio_id);
            $resultado = precioVenta($producto, $precio);
            
            $precios[] = $resultado['precio'];
            $producto = $resultado['producto'];
            
        }

        echo json_encode([
            'paginacion' => $paginacion->paginacion(),
            'productos' => $productos,
            'precios' => $precios
        ]);

        /// Ya deberia estar devolviendo el JSON adecuadamente, probar esto

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
