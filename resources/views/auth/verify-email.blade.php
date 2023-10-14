@extends('layouts.app')

@section('titulo')
    Debes confirmar tu email
@endsection

@section('contenido')

<div class="formulario__contenedor-md">

    <p class="mensaje__info">Te hemos enviado un correo electronico para que confirmes tu casilla de correo, no olvides revisar el spam.</p>

</div>

<div class="formulario__opciones-contenedor mb-10">
    <a href="{{ route('login') }}" class="formulario__opciones-enlace">Ya tienes una cuenta?</a>
</div>

@endsection

