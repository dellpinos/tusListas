@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Proveedores
@endsection

@section('contenido')

<div class="formulario__contenedor-boton">
    <a href="{{ route('provider.create') }}" class="provider__boton">Crear Proveedor</a>
    <div class="formulario__contenedor-busqueda">
        <i class="formulario__icono-busqueda fa-solid fa-magnifying-glass"></i>
        <input class="formulario__campo-busqueda" id="provider-formulario" type="text">
    </div>
</div>
<div class="swiper slider mb-10"> <!-- Swiper principal -->
    <div class="swiper-wrapper" id="providers-registros"> <!-- Swiper secundario -->
        {{-- Js --}}
    </div> <!-- Swiper secundario -->

    <div class="swiper-pagination"></div> <!-- Pagination -->

    <!-- Navegación -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

</div> <!-- Swiper principal -->
<div id="mensaje-vacio">
    {{-- Js --}}
</div>

@endsection


