@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Categorias
@endsection

@section('contenido')
    <div class="categoria__contenedor-boton" >
        <a href="{{ route('categoria.create') }}" class="categoria__boton">Crear Categoria</a>
        <a href="#" class="categoria__boton">Buscar Categoria</a>
    </div>

    <div class="swiper slider mb-10"> <!-- Swiper principal -->
        <div class="swiper-wrapper" id="categorias-registros"> <!-- Swiper secundario -->
    
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

