@extends('layouts.app')

@section('contenido')
    <div class="home__contenedor-principal">

        <h2 class="home__heading">
            Cambiar Password
        </h2>

        <div class="formulario__contenedor-md">

            <p class="mensaje__info mensaje__warning">Te hemos enviado un correo electronico para que confirmes tu casilla de
                correo, no olvides revisar el spam.</p>

        </div>

        <div class="formulario__opciones-contenedor mb-10">
            <a href="{{ route('login') }}" class="formulario__opciones-enlace">Ya tienes una cuenta?</a>
        </div>

    </div>
@endsection
