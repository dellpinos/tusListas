<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function index()
    {

        return view('ingreso.index');
    }
}
