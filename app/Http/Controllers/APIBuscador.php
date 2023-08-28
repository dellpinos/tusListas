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
    public function index()
    {
    }
    public function nombreProducto(Request $request)
    {

        // Busqueda segun coincidencia de 3 caracteres

        $patron = $request->input('input_producto');

        $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->get();

        echo json_encode($resultado);
    }
    public function productoIndividual(Request $request)
    {

        $id = $request->input('id');

        $producto = Producto::find($id);
        $precio = Precio::where('producto_id', $producto->id)->first();

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


        $resultado = [
            'producto' => $producto,
            'precio' => $precio
        ];

        echo json_encode($resultado);
    }
}
