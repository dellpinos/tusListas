<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class APICotDolar extends Controller
{
    // API Para consultar datos del Dolar en APIs externas

    public function consultaDolar() {

        
        $respuesta = Http::withHeaders([
            'Authorization' => 'Bearer '. env("DOL_TOKEN")
        ])      // Dolar blue dia anterior/cerrado
        ->get(env('DOL_URL_BLUE'));

        // Dolar blue dia anterior/cerrado
        // Retorna el historico del dolar (miles de registros), tomo el último indice del array

        $data = $respuesta->json();

        Dolar::create([
            "valor" => $data[count($data) - 1]["v"],
            "fecha" => $data[count($data) - 1]["d"]
        ]);

    }
}
