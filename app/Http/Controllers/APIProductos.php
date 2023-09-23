<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class APIProductos extends Controller
{
    // Agregar seguridad, sesion del usuario y sanitizacion de datos

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        // Devuelve todos los productos sin filtro ni busqueda

        $productos = Producto::orderBy('nombre', 'asc')->get();

        echo json_encode([
            'productos' => $productos
        ]);
    }
    public function destroy(Request $request)
    {

        // Debo recibir un id a eliminar y tengo que validar si es un fraccionado. Un fraccionado puede eliminarse
        // Un producto principal puede eliminarse pero debe eliminarse el fraccionado y debe informarse al usuario

        // Recibo el id de un producto:
        // 1_ este producto es fraccionado
        // 2_ no fraccionado y sin otro producto relacionado
        // 3_ no fraccionado y con otro producto relacionado

        // 1- se elimina el producto y precio
        // 2- se elimina el producto y precio
        // 3- consulta al usuario para eliminar 2 productos al mismo tiempo

        $producto = Producto::find($request->id);
        $precio = Precio::find($producto->precio_id);
        $productos = Producto::where('precio_id', $precio->id)->get();

        if ($request->confirm === "true") {
            
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

                foreach ($productos as $elemento) {
                    if ($elemento->unidad_fraccion !== null) {
                        $prod_fraccionado = $elemento;
                    } else {
                        $prod_no_fraccionado = $elemento;
                    }
                }

                if ($producto->id === $prod_fraccionado->id) {
                    // Es fraccionado, eliminar producto y precio
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
                } else if ($producto->id === $prod_no_fraccionado->id) {
                    // No es fraccionado, consultar al usuario
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

        $this->validate($request, [
            'producto_id' => 'integer|required',
            'cantidad' => 'integer|nullable',
            'precio' => 'numeric|required',
            'descuento' => 'numeric|nullable',
            'semanas' => 'integer',
            'dolar' => 'numeric|required',
        ]);

        $producto = Producto::find($request->producto_id);
        $precio = Precio::find($producto->precio_id);

        $precio->increment('contador_update');
        $precio->dolar = $request->dolar;
        $precio->precio = $request->precio;
        $precio->desc_porc = $request->descuento;
        $precio->desc_duracion = $request->semanas;
        
        if(!is_null($request->descuento)) {
            $precio->increment('desc_acu');
        }

        if(!is_null($request->cantidad)) {
            $producto->stock += $request->cantidad;
        }
        
        $resultado = $producto->save();
        if($resultado) {
            $respuesta = $precio->save();

            if($respuesta) {
                echo json_encode($respuesta);
            }
        } else {
            echo json_encode($resultado);
        }

    }
}





