<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use App\Models\Compra;
use App\Models\Precio;
use App\Models\Producto;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class APIProductos extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }

    public function all()
    {

        // Devuelve todos los productos sin filtro ni busqueda
        $productos = Producto::orderBy('nombre', 'asc')->where('empresa_id', session('empresa')->id)->get();

        echo json_encode([
            'productos' => $productos
        ]);
    }
    public function destroy(Request $request)
    {
        $id = filter_var($request->id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode("Algo salió mal :( ");
            return;
        }

        // Recibo el id de un producto:
        // 1_ Este producto es fraccionado
        // 2_ No fraccionado y sin otro producto relacionado
        // 3_ No fraccionado y con otro producto relacionado

        // 1- Se elimina el producto y precio
        // 2- Se elimina el producto y precio
        // 3- Consulta al usuario para eliminar 2 productos al mismo tiempo

        $producto = Producto::where('id', $id)->where('empresa_id', session('empresa')->id)->first();
        $precio = Precio::where('id', $producto->precio_id)->where('empresa_id', session('empresa')->id)->first();
        $productos = Producto::where('precio_id', $precio->id)->where('empresa_id', session('empresa')->id)->get();

        if ($request->confirm === "true") {

            // 3_ Confirmación del usuario, elimino ambos productos y el precio
            foreach ($productos as $elemento) {
                $elemento->delete();
            }
            $precio->delete();

            echo json_encode([
                'eliminado' => true,
            ]);
            return;
        } else {

            $prod_fraccionado = '';
            $prod_no_fraccionado = '';

            if ($productos->count() > 1) {
                // Identificar Fraccionado

                foreach ($productos as $elemento) {
                    if ($elemento->unidad_fraccion !== null) {
                        $prod_fraccionado = $elemento;
                    } else {
                        $prod_no_fraccionado = $elemento;
                    }
                }

                if ($producto->id === $prod_fraccionado->id) {

                    // Es fraccionado, eliminar producto (precio no es eliminado)
                    $respuesta = $producto->delete();

                    if ($respuesta) {

                        echo json_encode([
                            'eliminado' => true,
                        ]);
                        return;
                    } else {
                        echo json_encode([
                            'eliminado' => false,
                        ]);
                        return;
                    }
                } else if ($producto->id === $prod_no_fraccionado->id) {
                    // No es fraccionado, solicito confirmación del usuario
                    echo json_encode([
                        'eliminado' => false,
                        'eliminar_doble' => true
                    ]);
                    return;
                }
            } else {
                // No hay fraccionado, eliminar producto y precio
                $respuesta = $producto->delete();
                if ($respuesta) {
                    $precio->delete();

                    echo json_encode([
                        'eliminado' => true,
                    ]);
                    return;
                } else {
                    echo json_encode([
                        'eliminado' => false,
                    ]);
                    return;
                }
            }
        }
    }
    public function update(Request $request)
    {

        // Hay una validación previa en Js
        $this->validate($request, [
            'producto_id' => 'required|integer|min:1',
            'cantidad' => 'integer|nullable|min:1|max:999999',
            'precio' => 'numeric|required|max:99999999|min:0',
            'descuento' => 'numeric|nullable|min:1|max:999',
            'semanas' => 'integer|min:0|max:8',
            'dolar' => 'numeric|required|max:999999|min:0',
        ]);

        $producto = Producto::where('id', $request->producto_id)->where('empresa_id', session('empresa')->id)->first();
        $precio = Precio::where('id', $producto->precio_id)->where('empresa_id', session('empresa')->id)->first();

        $precio->increment('contador_update');
        $precio->precio = $request->precio;
        $precio->desc_porc = $request->descuento;
        $precio->desc_duracion = $request->semanas;

        // Evalua si modificar el dolar
        if (!$request->stock_flag) {
            // Ingreso mercaderia
            $precio->dolar = $request->dolar;
        }

        if (!is_null($request->descuento)) {
            $precio->increment('desc_acu');
        }

        if (!is_null($request->cantidad)) {

            // Evalua si modificar el stock o acumularlo
            if ($request->stock_flag) {
                // Revisión stock
                $producto->stock = $request->cantidad;
            } else {
                // Ingreso mercaderia
                $producto->stock += $request->cantidad;
            }
        }

        // Almacenar compra
        $compra = new APICompras;
        $compra_resultado = $compra->nueva_compra($request);

        if($compra_resultado) {

            $resultado = $producto->save();

            if ($resultado) {
                $respuesta = $precio->save();
    
                if ($respuesta) {
                    echo json_encode($respuesta);
                }
            } else {
                echo json_encode($resultado);
            }

        } else {

            echo json_encode([
                'mensaje' => "El monto de la compra es demasiado alto"
            ]);
        }
    }
}
