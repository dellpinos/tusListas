<?php

namespace App\Http\Controllers;

use App\Models\Aumento;
use App\Models\Empresa;
use App\Models\BlogPost;
use App\Models\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $empresas = Empresa::all()->count();
        $productos = Producto::all()->count();
        $aumentos = Aumento::all()->count();
        $posts = BlogPost::orderBy('created_at', 'desc')->limit(3)->get();
        $buscados = Producto::sum('contador_show');



        return view('home.index', [
            'empresas' => $empresas,
            'productos' => $productos,
            'aumentos' => $aumentos,
            'buscados' => $buscados,
            'posts' => $posts
        ]);
    }
}
