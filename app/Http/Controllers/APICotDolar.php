<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class APICotDolar extends Controller
{
    // API Para consultar datos del Dolar en APIs externas

    public function consultaDolar()
    {

        // Busco una consulta que coinncida con el dia de hoy
        $hoy = date('Y-m-d');
        $dolar_actual = Dolar::whereDate('created_at', $hoy)->get();

        if($dolar_actual->isEmpty()) {

            $respuesta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env("DOL_TOKEN")
            ])      // Dolar blue dia anterior/cerrado
                ->get(env('DOL_URL_BLUE'));
    
            // Dolar blue dia anterior/cerrado
            // Esta API retorna el historico del dolar (miles de registros), tomo el último indice del array
            $data = $respuesta->json();

            Dolar::create([
                "valor" => $data[count($data) - 1]["v"],
                "fecha" => $data[count($data) - 1]["d"]
            ]);
        }


    }
}
