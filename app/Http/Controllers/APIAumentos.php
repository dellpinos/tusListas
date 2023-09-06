<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Aumento;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Http\Request;


class APIAumentos extends Controller
{
    public function aumento_categoria(Request $request)
    {
        // dd($request->request);

        // Consultar Todos los productos que corresponden a esta categoria

        $precios = Precio::where('categoria_id', $request->categoria_id)->get();
        $categoria = Categoria::find($request->categoria_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Categoria',
            'nombre' => $categoria->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
    public function aumento_provider(Request $request)
    {

        $precios = Precio::where('provider_id', $request->provider_id)->get();
        $provider = Provider::find($request->provider_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Proveedor',
            'nombre' => $provider->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
    public function aumento_fabricante(Request $request)
    {

        $precios = Precio::where('fabricante_id', $request->fabricante_id)->get();
        $fabricante = Fabricante::find($request->fabricante_id);

        $preciosAfectados = count($precios);
        $porcentajeDecimal = 1 + ($request->porcentaje / 100);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * $porcentajeDecimal; // convierto porcentaje en decimal
            $precio->save();
        }

        Aumento::create([
            'porcentaje' => $porcentajeDecimal,
            'tipo' => 'Fabricante',
            'nombre' => $fabricante->nombre,
            'username' => auth()->user()->username,
            'afectados' => $preciosAfectados
        ]);

        echo json_encode($preciosAfectados);
    }
}
