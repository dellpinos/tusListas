<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APICalculos extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function calculo_ganancia(Request $request)
    {

        // Con la instancia de Validator puedo validar y luego leer los resultados de la validación
        $validator = Validator::make($request->all(), [
            'ganancia' => 'required|string',
            'id' => 'required|integer|min:1'
        ]);

        // La instancia de Validator me permite enviar al Frontend los resultados de la validación fallida
        if ($validator->fails()) {
            return json_encode([
                'errors' => $validator->errors(),
            ]);
        }

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
