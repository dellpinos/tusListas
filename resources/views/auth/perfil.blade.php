@extends('layouts.app')

@section('contenido')
    <div class="home__contenedor-principal">

        <h2 class="home__heading">

        </h2>

        <div class="formulario__contenedor width-full m-0">

            <p class="perfil__texto">Todavía estamos trabajando en esta parte, deberías <span class="perfil__texto-enlace">
                    <a href="{{ route('buscador') }}">volver</a>
                </span></p>

            {{-- <a class="formulario__boton" href="{{ route('password.request') }}">Cambiar password</a> --}}

            <div class="perfil__img">
                <img src="{{ asset('img/svg/vectorWorking.svg') }}" alt="Imagen en construcción">

            </div>


        </div>

    </div>
@endsection
