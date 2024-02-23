<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dolar;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class APIVentas extends Controller
{
    public function index()
    {
        $fecha_actual = Carbon::now();
        // Resto un año para tener un periodo, agrego un mes desde su primer dia.
        // Ejemplo de periodo: 01 marzo 2023 - 12 febrero 2024 (ignoro los dias restantes de febrero 2023)
        $fecha_inicio = $fecha_actual->copy()->subYear()->addMonth()->startOfMonth(); // genero una copia para no modificar la variable original

        // Esta consulta suma los montos y los agrupa por mes, retorna un array de meses con su ganancia
        $ganancias_por_mes_db = Venta::selectRaw('MONTH(created_at) as mes, SUM(monto_dolar) as ganancia')
            ->where('created_at', '>', $fecha_inicio) // Filtrar registros del último año
            ->where('empresa_id', session('empresa')->id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ganancias_mes_array = [];
        $ganancias_por_mes = [];

        foreach ($ganancias_por_mes_db as $ganancia) {
            $ganancias_mes_array[$ganancia->mes] = $ganancia->ganancia;
        }

        for ($i = 11; $i >= 0; $i--) {
            $mes = $fecha_actual->copy()->subMonths($i)->month; // Obtener el mes retrocediendo $i meses
            $nombre_mes = obtener_nombre_mes($mes);
            $ganancia_mes = isset($ganancias_mes_array[$mes]) ? $ganancias_mes_array[$mes] : 0;
            $ganancias_por_mes[] = [
                'mes' => $nombre_mes,
                'ganancia' => $ganancia_mes
            ];
        }

        return response()->json($ganancias_por_mes);
    }
    public function nueva_venta(Request $request)
    {

        // Dolar Actual
        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();
        $producto = Producto::find($request->id);

        // Venta sin descuento
        $calculo_ganancia = 0;
        $total = 0;
        $total_dolar = 0;
        $ganancia = 1;

        // Tipo de ganancia
        if ($producto->ganancia_tipo === 'provider') {
            $ganancia = $producto->provider->ganancia;
        } else if ($producto->ganancia_tipo === 'categoria') {
            $ganancia = $producto->categoria->ganancia;
        } else if ($producto->ganancia_tipo === 'producto') {
            $ganancia = $producto->ganancia_prod;
        }

        $venta = ($producto->precio->precio * 1.21) * $ganancia;

        // Calcular ingreso en dolares
        if (!$producto->precio->desc_porc) {
            // No tiene descuento
            $calculo_ganancia = $venta - ($producto->precio->precio * 1.21);
            $total = $calculo_ganancia * $request->cantidad_vendida;
            $total_dolar = $total / $dolar_hoy->valor;
        } else {
            // Tiene descuento - Aplicar descuento
            $venta_descuento = $venta - ($venta * ($producto->precio->desc_porc / 100));
            $calculo_ganancia = $venta_descuento - ($producto->precio->precio * 1.21);
            $total = $calculo_ganancia * $request->cantidad_vendida;
            $total_dolar = $total / $dolar_hoy->valor;
        }

        // No se resta el stock de productos fraccionados
        if (!$producto->unidad_fraccion) {
            // Actualizar stock del producto
            $producto->stock = $request->stock_restante;
            $producto->save();
        }

        $respuesta = Venta::create([
            'empresa_id' => session('empresa')->id,
            'producto_id' => $request->id,
            'monto' => $total,
            'monto_dolar' => $total_dolar
        ]);

        echo json_encode([
            'respuesta' => $respuesta
        ]);
    }
}
