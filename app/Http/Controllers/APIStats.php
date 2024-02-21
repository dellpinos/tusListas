<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class APIStats extends Controller
{
    // Validar rol del usuario

    public function buscados() {
        $buscados = Producto::orderBy('contador_show', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();
        
        $productos_todos = Producto::where('empresa_id', session('empresa')->id)->get();
        $precios_todos = Precio::where('empresa_id', session('empresa')->id)->get();
        $total_invertido = 0;

        $cantidad_por_categoria = [];
        $categorias_datos = [];
        $cantidad_por_provider = [];
        $providers_datos = [];
        $cantidad_por_fabricante = [];
        $fabricantes_datos = [];


        foreach($productos_todos as $producto) {

            if(array_key_exists($producto->categoria_id, $cantidad_por_categoria)){
                $cantidad_por_categoria[$producto->categoria_id]++;
            } else {
                $cantidad_por_categoria[$producto->categoria_id] = 1;
            }
            if(array_key_exists($producto->provider_id, $cantidad_por_provider)){
                $cantidad_por_provider[$producto->provider_id]++;
            } else {
                $cantidad_por_provider[$producto->provider_id] = 1;
            }
            if(array_key_exists($producto->fabricante_id, $cantidad_por_fabricante)){
                $cantidad_por_fabricante[$producto->fabricante_id]++;
            } else {
                $cantidad_por_fabricante[$producto->fabricante_id] = 1;
            }

            foreach ($precios_todos as $precio) {
                
                $total_invertido += $producto->stock * $precio->precio;

            }
        }


        foreach ($cantidad_por_categoria as $cat_id => $cantidad) {
            $categoria = Categoria::find($cat_id);

            $categorias_datos[] = [
                'id' => $cat_id,
                'nombre' => $categoria->nombre,
                'cantidad' => $cantidad
            ];
        }
        foreach ($cantidad_por_provider as $prov_id => $cantidad) {
            $provider = Provider::find($prov_id);

            $providers_datos[] = [
                'id' => $prov_id,
                'nombre' => $provider->nombre,
                'cantidad' => $cantidad
            ];
        }
        foreach ($cantidad_por_fabricante as $fab_id => $cantidad) {
            $fabricante = Fabricante::find($fab_id);

            $fabricantes_datos[] = [
                'id' => $fab_id,
                'nombre' => $fabricante->nombre,
                'cantidad' => $cantidad
            ];
        }
        
        echo json_encode([
            'buscados' => $buscados,
            'total_invertido' => $total_invertido,
            'total_productos' => count($productos_todos),
            'categorias_datos' => $categorias_datos,
            'providers_datos' => $providers_datos,
            'fabricantes_datos' => $fabricantes_datos
        ]);

    }

    // Puedo unir todas las consultas en un solo metodo, las stats siempre se van a cargar todas juntas

    public function stock()
    {
        $stock = Producto::orderBy('stock', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();

        
        echo json_encode([
            'stock' => $stock
        ]);

    }
}
