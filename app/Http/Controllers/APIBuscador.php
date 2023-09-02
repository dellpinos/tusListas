<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
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

        $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->get();


        echo json_encode($resultado);
    }
    public function producto_individual(Request $request)
    {


        $id = $request->input('id');

        $producto = Producto::find($id);
        $precio = Precio::find($producto->precio_id);

        $precio->updated_at = $precio->updated_at->subHours(3);

        $fabricante = Fabricante::find($producto->fabricante_id);
        $categoria = Categoria::find($producto->categoria_id);
        $provider = Provider::find($producto->provider_id);

        // Que ganancia aplica a este producto
        if (!$producto->ganancia_prod) {
            if ($producto->ganancia_tipo === 'proveedor') {
                $producto->ganancia = $provider->ganancia;
                $producto->ganancia_tipo = 'Proveedor';
            } else {
                $producto->ganancia = $categoria->ganancia;
                $producto->ganancia_tipo = 'Categoria';
            }
        } else {
            $producto->ganancia = $producto->ganancia_prod;
            $producto->ganancia_tipo = 'Producto';
        }

        $producto->venta = $producto->ganancia * ($precio->precio * 1.21);
        $producto->increment('contador_show');

        // Producto fraccionado
        if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {
            $producto->venta = $producto->ganancia * ($precio->precio * 1.21);
            $producto->venta = ($producto->venta * $producto->ganancia_fraccion) / $producto->contenido_total;
            $producto->venta = redondear($producto->venta);
        }

        $resultado = [
            'producto' => $producto,
            'precio' => $precio
        ];

        echo json_encode($resultado);
    }

    public function codigo_producto(Request $request)
    {
        $resultado = Producto::where('codigo', $request->codigo_producto)->get();

        if ($resultado->isEmpty()) {
            echo json_encode(false);
            return;
        }
        echo json_encode($resultado);
    }
}
