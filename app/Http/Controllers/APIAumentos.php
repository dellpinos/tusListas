<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Aumento;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Http\Request;


class APIAumentos extends Controller
{
    public function aumento_categoria(Request $request)
    {
        // dd($request->request);

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
    public function dolar_listado()
    {
        // 10 precios - productos con "dolar" mas bajo
        $precios = Precio::orderBy('dolar', 'asc')->limit(10)->get();

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
}
