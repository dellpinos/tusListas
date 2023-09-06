<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class APIAumentos extends Controller
{
    public function aumento_categoria(Request $request)
    {
        // dd($request->request);

        // Consultar Todos los productos que corresponden a esta categoria

        $precios = Precio::where('categoria_id', $request->categoria_id)->get();

        $preciosAfectados = count($precios);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * (1 + ($request->porcentaje / 100)); // convierto porcentaje en decimal
            $precio->save();
        }

        echo json_encode($preciosAfectados);
        // dd($precios);



        // Consultar todos los precios que corresponden a estos productos

        // Aumentar todos los precios acorde al porcentaje ingresado



    }
    public function aumento_provider(Request $request)
    {

        $precios = Precio::where('provider_id', $request->provider_id)->get();

        $preciosAfectados = count($precios);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * (1 + ($request->porcentaje / 100)); // convierto porcentaje en decimal
            $precio->save();
        }

        echo json_encode($preciosAfectados);

    }
    public function aumento_fabricante(Request $request)
    {

        $precios = Precio::where('fabricante_id', $request->fabricante_id)->get();

        $preciosAfectados = count($precios);

        foreach ($precios as $precio) {
            $precio->precio = $precio->precio * (1 + ($request->porcentaje / 100)); // convierto porcentaje en decimal
            $precio->save();
        }

        echo json_encode($preciosAfectados);
    }
}
