@extends('layouts.app')

@section('titulo')

@endsection

@section('contenido')


    <div class="formulario__contenedor width-full m-0">

        <p class="perfil__texto">Todavía estamos trabajando en esta parte, deberías <span class="perfil__texto-enlace">
        <a href="{{ route('buscador') }}">volver</a>    
        </span></p>

        {{-- <a class="formulario__boton" href="{{ route('password.request') }}">Cambiar password</a> --}}

        <div class="perfil__img">
            <img src="{{ asset('img/working.jpg') }}" alt="Imagen en construcción">

        </div>


    </div>



@endsection

