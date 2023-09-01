<?php

namespace App\Http\Controllers;


use App\Models\Provider;
use App\Models\Categoria;
use Illuminate\Http\Request;

class APICalculos extends Controller
{
    public function index()
    {
    }


    public function calculo_ganancia(Request $request)
    {


        $request->ganancia; // string
        $request->id; // id
        $respuesta = 0;

        if ($request->ganancia === 'categoria') {
            $categoria = Categoria::find($request->id);
            $respuesta = $categoria->ganancia;
        } elseif ($request->ganancia === 'proveedor') {
            $proveedor = Provider::find($request->id);
            $respuesta = $proveedor->ganancia;
        }

        echo json_encode($respuesta);
    }
}
