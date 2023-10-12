<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Paginacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
        $total_registros = Producto::where('empresa_id', session('empresa')->id)->get();

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

       $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros); // Creo la instancia con "la forma de una paginacion"

        if ($paginacion->totalPaginas() < $pagina_actual) {
            return json_encode("error");
        }

        $productos = Producto::orderBy('nombre', 'ASC')->where('empresa_id', session('empresa')->id)->offset($paginacion->offset())->limit($registros_por_pagina)->get();

        $precios = [];
        $resultado = [];

        foreach ($productos as $producto) {

            $precio = Precio::where('id', $producto->precio_id)->where('empresa_id', session('empresa')->id)->first();
            $resultado = precioVenta($producto, $precio);

            $precios[] = $resultado['precio'];
            $producto = $resultado['producto'];
        }

        echo json_encode([
            'paginacion' => $paginacion->paginacion(),
            'productos' => $productos,
            'precios' => $precios
        ]);
    }

    public function nombre_producto(Request $request)
    {

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'input_producto' => 'required|string|max:90|min:3'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
            ]);
        }

        // Busqueda segun coincidencia de 3 caracteres
        $patron = $request->input_producto;

        if ($request->filtro_frac) {
            // No retorna fraccionados, esto es util en buscadores que modifican registros
            $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->where('empresa_id', session('empresa')->id)->whereNull('ganancia_fraccion')->get();
        } else {

            $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->where('empresa_id', session('empresa')->id)->get();
        }

        echo json_encode($resultado);
    }
    public function producto_individual(Request $request)
    {

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
            ]);
        }

        $id = $request->id;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $producto = Producto::where('id', $id)->where('empresa_id', session('empresa')->id)->first();
        $precio = Precio::where('id', $producto->precio_id)->where('empresa_id', session('empresa')->id)->first();

        // Calcular precio de venta
        $resultado = precioVenta($producto, $precio);

        echo json_encode($resultado);
    }

    public function codigo_producto(Request $request)
    {

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'codigo_producto' => 'required|string|min:4|max:4'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
            ]);
        }

        $codigo = strtolower($request->codigo_producto);

        // Filtra fraccionados
        if ($request->filtro_frac) {
            // No retorna fraccionados, esto es util en buscadores que modifican registros
            $resultado = Producto::where('codigo', $codigo)->where('empresa_id', session('empresa')->id)->whereNull('ganancia_fraccion')->get();
        } else {

            $resultado = Producto::where('codigo', $codigo)->where('empresa_id', session('empresa')->id)->get();
        }

        if ($resultado->isEmpty()) {
            echo json_encode(false);
            return;
        }
        echo json_encode($resultado);
    }
}
