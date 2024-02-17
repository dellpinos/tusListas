<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class APIStats extends Controller
{
    // Validar rol del usuario

    public function buscados() {
        $buscados = Producto::orderBy('contador_show', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();
        
        $productos_todos = Producto::where('empresa_id', session('empresa')->id)->get();
        $precios_todos = Precio::where('empresa_id', session('empresa')->id)->get();
        $total_invertido = 0;

        foreach($productos_todos as $producto) {

            foreach ($precios_todos as $precio) {
                
                $total_invertido += $producto->stock * $precio->precio;

            }
        }

        
        echo json_encode([
            'buscados' => $buscados,
            'total_invertido' =>$total_invertido
        ]);

    }

    // Puedo unir todas las consultas en un solo metodo, las stats siempre se van a cargar todas juntas

    public function stock() {
        $stock = Producto::orderBy('stock', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();

        
        echo json_encode([
            'stock' => $stock
        ]);

    }
}
