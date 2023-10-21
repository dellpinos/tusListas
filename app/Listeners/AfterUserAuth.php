<?php

namespace App\Listeners;

use App\Models\Empresa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterUserAuth
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        // Incluyo la empresa del usuario en su sesión
        $user = $event->user;

        $empresa = Empresa::find($user->empresa_id);

        session()->put('empresa', $empresa);

    }
}
