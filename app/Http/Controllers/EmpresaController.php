<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dolar;
use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'empresa.asignar']);
    }

    public function index()
    {
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return redirect(route('buscador'));
        }

        $users = User::orderBy('name', 'asc')->where('empresa_id', session('empresa')->id)->count();

        return view('empresa.owner-tools', [
            'contador_users' => $users
        ]);
    }

    public function stock()
    {

        return view('empresa.stock');
    }

    public function estadisticas()
    {

        $productos_todos = Producto::where('empresa_id', session('empresa')->id)->get();
        $precios_todos = Precio::where('empresa_id', session('empresa')->id)->get();
        $precios_principales_desc = Precio::orderBy('desc_porc', 'DESC')->where('empresa_id', session('empresa')->id)->limit(5)->get();
        $dolar_hoy = Dolar::orderBy('fecha', 'DESC')->first();
        $total_invertido = 0;
        $productos_descuento = 0;
        $stock_critico = 0;
        $productos_principales_desc = [];


        foreach ($precios_principales_desc as $precio_desc) {

            foreach ($productos_todos as $producto) {

                if ($producto->precio_id === $precio_desc->id) {



                    $productos_principales_desc[] = $producto;
                }
            }
        }



        foreach ($productos_todos as $producto) {




            foreach ($precios_todos as $precio) {

                if ($producto->precio_id === $precio->id) {

                    if ($precio->desc_porc !== null) {
                        $productos_descuento++;
                    }
                    if ($producto->stock <= 1) {
                        $stock_critico++;
                    }
                    $total_invertido += $producto->stock * $precio->precio;
                }
            }
        }


        return view('empresa.estadisticas', [
            "total_invertido" => number_format($total_invertido, 0, ',', '.'),
            "productos_descuento" => $productos_descuento,
            "stock_critico" => $stock_critico,
            "dolar_hoy" => $dolar_hoy,
            "productos_principales_desc" => $productos_principales_desc
        ]);
    }
}
