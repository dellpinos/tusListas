<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Aumento;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use App\Models\Paginacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APIAumentos extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function aumento_categoria(Request $request)
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'integer|required|min:1',
            'porcentaje' => 'integer|required|min:1|max:500'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
                'tipo' => 'categoria'
            ]);
        }

        // Consultar Todos los productos que corresponden a esta categoria y la empresa dentro sesión actual
        $precios = Precio::where('categoria_id', $request->categoria_id)->where('empresa_id', session('empresa')->id)->get();

        $categoria = Categoria::where('id', $request->categoria_id)->where('empresa_id', session('empresa')->id)->first();

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
            'afectados' => $preciosAfectados,
            'empresa_id' => session('empresa')->id
        ]);

        return json_encode([
            'afectados' => $preciosAfectados,
            'errors' => null
        ]);
    }

    public function aumento_provider(Request $request)
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'provider_id' => 'integer|required|min:1',
            'porcentaje' => 'integer|required|min:1|max:500'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
                'tipo' => 'provider'
            ]);
        }

        $precios = Precio::where('provider_id', $request->provider_id)->where('empresa_id', session('empresa')->id)->get();
        $provider = Provider::where('id', $request->provider_id)->where('empresa_id', session('empresa')->id)->first();

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
            'afectados' => $preciosAfectados,
            'empresa_id' => session('empresa')->id
        ]);

        echo json_encode($preciosAfectados);
    }
    public function aumento_fabricante(Request $request)
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'fabricante_id' => 'integer|required|min:1',
            'porcentaje' => 'integer|required|min:1|max:500'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
                'tipo' => 'fabricante'
            ]);
        }

        $precios = Precio::where('fabricante_id', $request->fabricante_id)->where('empresa_id', session('empresa')->id)->get();
        $fabricante = Fabricante::where('id', $request->fabricante_id)->where('empresa_id', session('empresa')->id)->first();

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
            'afectados' => $preciosAfectados,
            'empresa_id' => session('empresa')->id
        ]);

        return json_encode([
            'afectados' => $preciosAfectados,
            'errors' => false
        ]);
    }
    public function dolar_busqueda(Request $request) // <<<<<<
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'page' => 'integer|required|min:1',
            'valor' => 'integer|required|min:0|max:10000'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors()
            ]);
        }

        $registros_por_pagina = 10;

        $input = $request->valor;
        $pagina_actual = $request->page;

        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        $total_registros = Precio::where('dolar', "<", $input)->where('empresa_id', session('empresa')->id)->count();

        if (!$pagina_actual || $pagina_actual < 1) {
            return json_encode("error");
        }

        if ($total_registros < 1) {
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

        $precios = Precio::where('dolar', "<", $input)->orderBy('dolar', 'ASC')->offset($paginacion->offset())->limit($registros_por_pagina)->get();

        $productos = [];
        $resultado = [];
        foreach ($precios as $precio) {
            $productosTodos = Producto::where('precio_id', $precio->id)->where('empresa_id', session('empresa')->id)->get();

            if ($productosTodos->count() === 0) {
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
            'precios' => $precios,
            'errors' => false
        ]);
    }

    public function dolar_listado()
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // 10 precios - productos con "dolar" mas bajo
        $precios = Precio::orderBy('dolar', 'asc')->where('empresa_id', session('empresa')->id)->limit(5)->get();

        $productos = [];
        $resultado = [];
        foreach ($precios as $precio) {
            $productosTodos = Producto::where('precio_id', $precio->id)->where('empresa_id', session('empresa')->id)->get();

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
        echo json_encode(
            [
                'precios' => $precios,
                'productos' => $productos
            ]
        );
    }

    public function dolar_count(Request $request)
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'valor' => 'integer|required|min:0|max:10000'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors()
            ]);
        }

        // Cuantos registros serán afectados
        $input = $request->valor;
        $resultado = Precio::where('dolar', "<", $input)->where('empresa_id', session('empresa')->id)->count();

        return json_encode([
            'afectados' => $resultado,
            'errors' => false
        ]);
    }

    public function dolar_update(Request $request)
    {

        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'valor' => 'integer|required|min:0|max:10000'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors()
            ]);
        }

        $input = $request->valor;
        $afectados = $request->afectados;

        $input = filter_var($input, FILTER_VALIDATE_INT);
        $afectados = filter_var($afectados, FILTER_VALIDATE_INT);

        if (!$input || !$afectados) {
            echo json_encode(false);
            return;
        }

        $precios = Precio::where('dolar', "<", $input)->where('empresa_id', session('empresa')->id)->get();

        foreach ($precios as $precio) {

            $porc_aumento = $input / $precio->dolar;
            $precio->precio = $porc_aumento * $precio->precio;
            $precio->dolar = $input;

            $precio->save();
            $precio->increment('contador_update');
        }

        Aumento::create([
            'porcentaje' => 0, // Este porcentaje depende del desfasaje de cada producto, solo podria calcularse un promedio
            'tipo' => 'Dolar',
            'nombre' => 'Varios',
            'username' => auth()->user()->username,
            'afectados' => $afectados,
            'empresa_id' => session('empresa')->id
        ]);

        echo json_encode(true);
    }
}
