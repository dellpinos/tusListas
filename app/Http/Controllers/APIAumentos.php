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
    ///////////////


    // tengo una funcion que devuelve una lista completa del listado dolar, retorna 10 registros (los mas bajos)

    // esto debe ser reemplazado cuando el usuario pide todos los registros desactualizados
    // necesito otra funcion que devuelva todos estos registros
    // estos registros estan paginados, la funcion no carga todos los registros a la vez. 
    // Lo hace acorde a la pagina que este visitando el usuario 


    // Como se ejecuta el codigo de la pagina?
    // El paginador tiene enlace Anterior y Siguiente que sirven para volver a disparar la funcion q anterior
    // La clase paginador imprime html en la vista (botones y numero de pagina), puedo hacerlo con Js
    // inlcuyendo los enlaces

    // Los modelos (el modelo a paginar) tienen el metodo "paginar" que es la consulta a la base de datos, las consultas
    // a la DB se hacen mediante los modelos.
    // - En mi proyecto estoy paginando los resultados que provienen de diferentes modelos
    // debo ejecutar la consulta a la base de datos en los controladores y unir estas respuestas




    // todo es consultado y devuelto por js
    // listener al boton que presiona el usuario
    // en un FormData envio el numero a evaluar, con este consulto cantidad en la db
    // 

    // Tengo que calcular el numero de pagina con Js, 
    // pagina actual lo calcula js? VirtualDOM?

    // ese numero recibido en un post que visita - Js se comunica enviando un post y recibe lo que un endpoint
    // consulta a otro -
    //  otra url GET (api) que devuelve todos los registros a la primera, retorna un json con los registros
    // que correspondan pero paginados (una variable con cantidad de paginas, otra con pagina actual, otra
    // con todos los registros)
    
    // offset y limit son offset() y registros por pagina


    // Otra ruta GET, 

    // Cuando el usuario ingresa el dolar que quiere comprobar se envia un POST desde Js
    // Este POST solo contiene el número de dolar y un número de página (por default es 1)
    // El controlador consulta la base de datos para recuperar una cantidad de precios/productos
    // acorde al offset calculado (retorna la cantidad de registros que corresponden a la pagina proporcionada por el cliente)
    // tambien retorna el bloque de HTML que corresponde a la paginación, la cantidad de paginas y los botones?

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
        


        // Puedo enviar el html de la paginacion (anterior/siguiente y numero de paginas)
        // envio todos los precios y productos que correspondan


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

}
