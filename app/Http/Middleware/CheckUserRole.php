<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        /// ???????
        dd("hola");
        // Evalua el rol del usuario
        if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin') {
            return json_encode([
                'error' => "Usuario invalido",
            ]);
        }


        return $next($request);
    }
}
