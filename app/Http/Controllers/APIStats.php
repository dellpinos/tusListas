<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class APIStats extends Controller
{
    // Validar rol del usuario

    public function buscados() {
        $buscados = Producto::orderBy('contador_show', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();

        echo json_encode([
            'buscados' => $buscados
        ]);

    }

    // Puedo unir todas las consultas en un solo metodo, las stats siempre se van a cargar todas juntas

    public function stock() {
        $stock = Producto::orderBy('stock', 'DESC')->where('empresa_id', session('empresa')->id)->limit(10)->get();

        
        echo json_encode([
            'stock' => $stock
        ]);

    }
}
