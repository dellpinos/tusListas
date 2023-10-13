<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Str;

class APICodigo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function generar_codigo()
    {

        $codigo = generarCodigo(); // helper

        echo json_encode($codigo);


    }
}