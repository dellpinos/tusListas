<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class APITutorial extends Controller
{
    public function consulta ()
    {

        $consulta = auth()->user()->tutorial;

        echo json_encode($consulta);


    }

    public function modificar (Request $request)
    {

        $user = User::find(auth()->user()->id);
        $user->tutorial = $request->modificar;

        $user->save();
        

        dd($user);
    }
}
