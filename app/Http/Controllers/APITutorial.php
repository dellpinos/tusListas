<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class APITutorial extends Controller
{
    public function consulta ()
    {

        $user = User::find(auth()->user()->id);

        $consulta = $user->tutorial;
        $consulta_lvl = $user->tutorial_lvl;

        echo json_encode([
            'tutorial' => $consulta,
            'tutorial_lvl' => $consulta_lvl
        ]);
    }

    public function modificar (Request $request)
    {

        $user = User::find(auth()->user()->id);
        $user->tutorial = $request->modificar;
        $user->tutorial_lvl = 0;

        $resultado = $user->save();

        echo json_encode($resultado);
    }

    public function set_Lvl (Request $request)
    {

        $user = User::find(auth()->user()->id);
        $user->tutorial_lvl = $request->lvl;

        $resultado = $user->save();

        echo json_encode($resultado);
    }
}
