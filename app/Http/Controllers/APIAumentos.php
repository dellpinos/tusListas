<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Aumento;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use App\Helpers\Paginacion;
use Illuminate\Http\Request;


class APIAumentos extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function aumento_categoria(Request $request)
    {

        // Consultar Todos los productos que corresponden a esta categoria
        $precios = Precio::where('categoria_id', $request->categoria_id)->get();
        $categoria = Categoria::find($request->categoria_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Categoria',
            'nombre' => $categoria->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
    public function aumento_provider(Request $request)
    {

        $precios = Precio::where('provider_id', $request->provider_id)->get();
        $provider = Provider::find($request->provider_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Proveedor',
            'nombre' => $provider->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
    public function aumento_fabricante(Request $request)
    {

        $precios = Precio::where('fabricante_id', $request->fabricante_id)->get();
        $fabricante = Fabricante::find($request->fabricante_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Fabricante',
            'nombre' => $fabricante->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
    public function dolar_busqueda(Request $request)
    {

        $input = $request->valor;
        $pagina_actual = $request->page;

        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 1) {
            return dd('error');
        }

        $total_registros = Precio::where('dolar', "<" ,$input)->count();

        if($total_registros < 1) {
            echo json_encode([
                'productos' => false,
                'precios' => false
            ]);
            return;
        }

        $registros_por_pagina = 10;

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros); // creo la instancia con "la forma de una paginacion"


        if ($paginacion->totalPaginas() < $pagina_actual) {
            return dd('error');
        }

        $productos = [];

        $precios = Precio::where('dolar', "<", $input)->orderBy('dolar', 'ASC')->offset($paginacion->offset())->limit($registros_por_pagina)->get();

        $productos = [];
        $resultado = [];
        foreach ($precios as $precio) {
            $productosTodos = Producto::where('precio_id', $precio->id)->get();

            if($productosTodos->count() === 0) {
                $precio->delete(); //// PROVISORIO, elimina un precio sin producto. Resolver al trabajar en delete() de registros
                return; // retorna, hay que recargar la página para volver a ejecutar hasta que no queden precios sin producto
            }
            if ($productosTodos->count() > 1) {
                // Existe Fraccionado
                foreach ($productosTodos as $producto) {
                    if ($producto->unidad_fraccion === null && $producto->contenido_total === null && $producto->ganancia_fraccion === null) {
                        // No es el fraccionado
                        $resultado = precioVenta($producto, $precio);
                        $productos[] = $resultado['producto'];
                        $precio = $resultado['precio'];
                    }
                }
            } else {
                // No existe fraccionado
                $resultado = precioVenta($productosTodos->first(), $precio);
                $productos[] = $resultado['producto'];
                $precio = $resultado['precio'];
            }
        }
    
        echo json_encode([
            'paginacion' => $paginacion->paginacion(),
            'productos' => $productos,
            'precios' => $precios
        ]);
    }


    ///////////////
    public function dolar_listado()
    {
        // 10 precios - productos con "dolar" mas bajo
        $precios = Precio::orderBy('dolar', 'asc')->limit(5)->get();


        $productos = [];
        $resultado = [];
        foreach ($precios as $precio) {
            $productosTodos = Producto::where('precio_id', $precio->id)->get();

            if($productosTodos->count() === 0) {
                $precio->delete(); //// PROVISORIO, elimina un precio sin producto. Resolver al trabajar en delete() de registros
                return; // retorna, hay que recargar la página para volver a ejecutar hasta que no queden precios sin producto
            }
            if ($productosTodos->count() > 1) {
                // Existe Fraccionado
                foreach ($productosTodos as $producto) {
                    if ($producto->unidad_fraccion === null && $producto->contenido_total === null && $producto->ganancia_fraccion === null) {
                        // No es el fraccionado
                        $resultado = precioVenta($producto, $precio);
                        $productos[] = $resultado['producto'];
                        $precio = $resultado['precio'];
                    }
                }
            } else {
                // No existe fraccionado
                $resultado = precioVenta($productosTodos->first(), $precio);
                $productos[] = $resultado['producto'];
                $precio = $resultado['precio'];
            }
        }
        echo json_encode([
            'precios' => $precios, 
            'productos' => $productos]
        );
    }

    public function dolar_count(Request $request)
    {
        // Cuantos registros serán afectados
        $input = $request->valor;
        $resultado = Precio::where('dolar', "<" ,$input)->count();

        echo json_encode($resultado);
    }

    public function dolar_update(Request $request)
    {
        
        $input = $request->valor;
        $afectados = $request->afectados;

        $input = filter_var($input, FILTER_VALIDATE_INT);
        $afectados = filter_var($afectados, FILTER_VALIDATE_INT);

        if(!$input || !$afectados) {
            echo json_encode(false);
            return;
        }


        $precios = Precio::where('dolar', "<" ,$input)->get();

        foreach($precios as $precio) {

            $porc_aumento = $input / $precio->dolar; 
            $precio->precio = $porc_aumento * $precio->precio;
            $precio->dolar = $input;


            $precio->save();
            $precio->increment('contador_update');
        }
        Aumento::create([
            'porcentaje' => 0,
            'tipo' => 'Dolar',
            'nombre' => 'Varios',
            'username' => auth()->user()->username,
            'afectados' => $afectados
        ]);

        echo json_encode(true);
    }

}
