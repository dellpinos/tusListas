<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dolar;
use App\Models\Compra;
use Illuminate\Http\Request;

class APICompras extends Controller
{
    public function index()
    {
        $fecha_actual = Carbon::now();
        // Resto un año para tener un periodo, agrego un mes desde su primer dia.
        // Ejemplo de periodo: 01 marzo 2023 - 12 febrero 2024 (ignoro los dias restantes de febrero 2023)
        $fecha_inicio = $fecha_actual->copy()->subYear()->addMonth()->startOfMonth(); // genero una copia para no modificar la variable original

        // Esta consulta suma los montos y los agrupa por mes, retorna un array de meses con su ganancia
        $gastos_por_mes_db = Compra::selectRaw('MONTH(created_at) as mes, SUM(monto_dolar) as gasto')
            ->where('created_at', '>', $fecha_inicio) // Filtrar registros del último año
            ->where('empresa_id', session('empresa')->id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $gastos_mes_array = [];
        $gastos_por_mes = [];

        foreach ($gastos_por_mes_db as $gasto) {
            $gastos_mes_array[$gasto->mes] = $gasto->gasto;
        }

        for ($i = 11; $i >= 0; $i--) {
            $mes = $fecha_actual->copy()->subMonths($i)->month; // Obtener el mes retrocediendo $i meses
            $nombre_mes = obtener_nombre_mes($mes);
            $gasto_mes = isset($gastos_mes_array[$mes]) ? $gastos_mes_array[$mes] : 0;
            $gastos_por_mes[] = [
                'mes' => $nombre_mes,
                'gasto' => $gasto_mes
            ];
        }

        return response()->json($gastos_por_mes);
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
