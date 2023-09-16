<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class APICategorias extends Controller
{
    // Agregar seguridad, sesion del usuario y sanitizacion de datos

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all()
    {

        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        echo json_encode([
            'categorias' => $categorias
        ]);
    }

    public function destroy(Request $request)
    {
        // Verifico que no haya productos ni precios relacionados a esta categoria
        $productos = Producto::where('categoria_id', $request->id)->get();

        if ($productos->count() === 0) {
            $precios = Precio::where('categoria_id', $request->id)->get();
            if ($precios->count() === 0) {

                // Eliminar
                $categoria = Categoria::find($request->id);

                $respuesta = $categoria->delete();
                if ($respuesta) {
                    echo json_encode([
                        'eliminado' => true
                    ]);
                } else {
                    echo json_encode([
                        'cantidad_productos' => 'Algo salio mal :(',
                        'eliminado' => false
                    ]);
                }
            }
        } else {
            // No se puede eliminar
            $productos_count = $productos->count(); // "Esta categoria tiene X productos asignados"

            echo json_encode([
                'cantidad_productos' => $productos_count,
                'eliminado' => false
            ]);
        }
    }
}
