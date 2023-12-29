<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsignarEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Incluyo la empresa del usuario en su sesión
        $user = auth()->user();

        $empresa = Empresa::find($user->empresa_id);

        session()->put('empresa', $empresa);


        return $next($request);
    }
}
