<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class APIBuscador extends Controller
{
    public function index()
    {

    }
    public function nombreProducto(Request $request){

        // Busqueda segun coincidencia de 3 caracteres
        // $patron = $request->patron;


        $patron = $request->input('input_producto');


        $resultado = Producto::where('nombre', 'LIKE', '%' . $patron . '%')->get();

        echo json_encode($resultado);

    }
}
