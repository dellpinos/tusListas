<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class APIFabricantes extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {

        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();

        echo json_encode([
            'fabricantes' => $fabricantes
        ]);
    }

    public function destroy(Request $request)
    {

        $id = filter_var($request->id, FILTER_VALIDATE_INT);
        // Verifico que no haya productos ni precios relacionados a esta fabricante
        $productos = Producto::where('fabricante_id', $id)->get();

        if ($productos->count() === 0 && $id !== false) {
            $precios = Precio::where('fabricante_id', $id)->get();
            if ($precios->count() === 0) {

                // Eliminar
                $fabricante = Fabricante::find($id);

                $respuesta = $fabricante->delete();
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
            $productos_count = $productos->count(); // "Esta fabricante tiene X productos asignados"

            echo json_encode([
                'cantidad_productos' => $productos_count,
                'eliminado' => false
            ]);
        }
    }
}
