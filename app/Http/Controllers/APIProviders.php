<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use Illuminate\Http\Request;

class APIProviders extends Controller
{
    // Agregar seguridad, sesion del usuario y sanitizacion de datos
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }

    public function all()
    {

        $providers = Provider::orderBy('nombre', 'asc')->where('empresa_id', session('empresa')->id)->get();

        echo json_encode([
            'providers' => $providers
        ]);
    }

    public function destroy(Request $request)
    {
        $id = filter_var($request->id, FILTER_VALIDATE_INT);

        // Verifico que no haya productos ni precios relacionados a esta provider
        $productos = Producto::where('provider_id', $id)->where('empresa_id', session('empresa')->id)->get();

        if ($productos->count() === 0) {
            $precios = Precio::where('provider_id', $id)->where('empresa_id', session('empresa')->id)->get();

            if ($precios->count() === 0) {

                // Eliminar
                $provider = Provider::where('id', $id)->where('empresa_id', session('empresa')->id)->first();

                $respuesta = $provider->delete();
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
            $productos_count = $productos->count(); // "Esta provider tiene X productos asignados"

            echo json_encode([
                'cantidad_productos' => $productos_count,
                'eliminado' => false
            ]);
        }
    }
}
