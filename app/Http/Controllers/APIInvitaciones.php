<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIInvitaciones extends Controller
{
    static function create(Request $request)
    {
        // Crear una invitación y enviar un email
        dd($request->request);


    }
}
