@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Fabricantes
@endsection

@section('contenido')

<div class="formulario__contenedor-boton">
    <a href="{{ route('agenda') }}" class="fabricante__boton">&laquo; Volver</a>
    <a href="{{ route('fabricante.create') }}" class="fabricante__boton">Crear Fabricante</a>
    <div class="formulario__contenedor-busqueda">
        <i class="formulario__icono-busqueda fa-solid fa-magnifying-glass"></i>
        <input class="formulario__campo-busqueda" id="fabricante-formulario" type="text">
    </div>
</div>

<div class="swiper slider mb-10"> <!-- Swiper principal -->
    <div class="swiper-wrapper" id="fabricantes-registros"> <!-- Swiper secundario -->

    </div> <!-- Swiper secundario -->

    <div class="swiper-pagination"></div> <!-- Pagination -->

    <!-- Navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

</div> <!-- Swiper principal -->

<div id="mensaje-vacio">
    {{-- Js --}}
</div>

@endsection















{{-- <<<<<<<<<<<< --}}



