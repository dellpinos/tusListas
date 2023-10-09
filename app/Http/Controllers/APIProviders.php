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
                $this->middleware('auth');
            }

            public function all()
            {
        
                $providers = Provider::orderBy('nombre', 'asc')->get();
        
                echo json_encode([
                    'providers' => $providers
                ]);
            }
        
            public function destroy(Request $request)
            {
                $id = filter_var($request->id, FILTER_VALIDATE_INT);

                // Verifico que no haya productos ni precios relacionados a esta provider
                $productos = Producto::where('provider_id', $id)->get();
        
                if ($productos->count() === 0) {
                    $precios = Precio::where('provider_id', $id)->get();
                    
                    if ($precios->count() === 0) {
        
                        // Eliminar
                        $provider = Provider::find($id);
        
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
