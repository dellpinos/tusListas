<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Paginacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fabricante;
use App\Models\Provider;
use Illuminate\Support\Facades\Validator;

class APIBuscador extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }

    public function index(Request $request)
    {

        $busc_nombre = $request->termino;
        $busc_categoria = $request->categoria;
        $busc_fabricante = $request->fabricante;
        $busc_provider = $request->provider;

        // Orden - ASC o DESC
        $orden = $request->orden ?? "ASC";


        $registros_por_pagina = 15;
        $pagina_actual = $request->page;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);



        $total_registros = Producto::orderBy('nombre', $orden)
        ->when($busc_nombre, function ($query) use ($busc_nombre) {
            $query->where('nombre', 'LIKE', "%" . $busc_nombre . "%");
        })
        ->when($busc_categoria, function ($query) use ($busc_categoria) {
            $query->where('categoria_id', $busc_categoria);
        })
        ->when($busc_fabricante, function ($query) use ($busc_fabricante) {
            $query->where('fabricante_id', $busc_fabricante);
        })
        ->when($busc_provider, function ($query) use ($busc_provider) {
            $query->where('provider_id', $busc_provider);
        })
        ->where('empresa_id', session('empresa')->id)
        ->count();







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









        // Agregar condiciones de filtros

        // $productos = Producto::orderBy('nombre', 'ASC')->where('empresa_id', session('empresa')->id)->offset($paginacion->offset())->limit($registros_por_pagina)->get();




        // Consulta y ordenamientos:






        $productos = Producto::orderBy('nombre', $orden)
            ->when($busc_nombre, function ($query) use ($busc_nombre) {
                $query->where('nombre', 'LIKE', "%" . $busc_nombre . "%");
            })

            ->when($busc_categoria, function ($query) use ($busc_categoria) {
                $query->where('categoria_id', $busc_categoria);
            })
            ->when($busc_fabricante, function ($query) use ($busc_fabricante) {
                $query->where('fabricante_id', $busc_fabricante);
            })
            ->when($busc_provider, function ($query) use ($busc_provider) {
                $query->where('provider_id', $busc_provider);
            })

            ->where('empresa_id', session('empresa')->id)
            ->offset($paginacion->offset())
            ->limit($registros_por_pagina)
            ->get();






        // $vacantes = Vacante::when($this->termino, function($query) {
        //     $query->where('titulo', 'LIKE', "%" . $this->termino . "%");
        // })
        // ->when($this->termino, function($query) {
        //     $query->orWhere('empresa', 'LIKE', "%" . $this->termino . "%");
        // })
        // ->when($this->categoria, function($query) {
        //     $query->where('categoria_id', $this->categoria);
        // })
        // ->when($this->salario, function($query) {
        //     $query->where('salario_id', $this->salario);
        // })
        // ->paginate(20);






        // $productos = Producto::orderBy('nombre', 'ASC')->where('empresa_id', session('empresa')->id)->offset($paginacion->offset())->limit($registros_por_pagina)->get();

        $precios = [];
        $resultado = [];


        foreach ($productos as $producto) {

            $precio = Precio::where('id', $producto->precio_id)->where('empresa_id', session('empresa')->id)->first();
            $categoria = Categoria::where('id', $producto->categoria_id)->where('empresa_id', session('empresa')->id)->first();
            // buscar categoria de cada uno
            $resultado = precioVenta($producto, $precio);

            $precios[] = $resultado['precio'];
            $producto = $resultado['producto'];
            $producto->categoria = $categoria->nombre;

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
            'codigo_producto' => 'required|string|min:5|max:5'
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

    public function consultar_CFP()
    {
        // Categorias, Fabricantes y Proveedores

        $categorias = Categoria::where('empresa_id', session('empresa')->id)->get();
        $providers = Provider::where('empresa_id', session('empresa')->id)->get();
        $fabricantes = Fabricante::where('empresa_id', session('empresa')->id)->get();

        echo json_encode([
            'categorias' => $categorias,
            'providers' => $providers,
            'fabricantes' => $fabricantes
        ]);
    }
}
