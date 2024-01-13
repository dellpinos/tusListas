<?php

namespace App\Http\Controllers;

use App\Models\Aumento;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $empresas = Empresa::all()->count();
        $productos = Producto::all()->count();
        $aumentos = Aumento::all()->count();
        $buscados = Producto::sum('contador_show');



        return view('home.index', [
            'empresas' => $empresas,
            'productos' => $productos,
            'aumentos' => $aumentos,
            'buscados' => $buscados
        ]);
    }
}
