<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use App\Models\Compra;
use Illuminate\Http\Request;

class APICompras extends Controller
{
    public function index()
    {
        $compras = Compra::all();

        echo json_encode([
            'compras' => $compras
        ]);
    }
    public function nueva_compra(Request $request)
    {
        // Dolar Actual
        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();
        $monto_compra = intval($request->precio) * $request->cantidad;
        $monto_compra_dolar = (intval($request->precio) * $request->cantidad) / intval($dolar_hoy->valor);

        // Almaceno compra
        $compra = Compra::create([
            'monto' => $monto_compra,
            'monto_dolar' => $monto_compra_dolar,
            'empresa_id' => session('empresa')->id,
        ]);
    }

}
