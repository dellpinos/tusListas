<?php

namespace App\Http\Controllers;


use App\Models\Provider;
use App\Models\Categoria;
use Illuminate\Http\Request;

class APICalculos extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
        } elseif ($request->ganancia === 'provider') {
            $provider = Provider::find($request->id);
            $respuesta = $provider->ganancia;
        }

        echo json_encode($respuesta);
    }
}
