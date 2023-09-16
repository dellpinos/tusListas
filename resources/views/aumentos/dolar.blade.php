@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('titulo')
    Aumento en Base al U$S
@endsection

@section('contenido')

    <div class="aumento-formulario__contenedor">

        <h3>Dolar al dia de la fecha</h3>

        <div class="aumento-formulario__campo-flex">
            <label for="aumento-dolar" class="formulario__label">U$S</label>

            <input type="number" id="aumento-dolar" placeholder="Dolar Hoy" class="formulario__campo">
        </div>

        <a class="formulario__boton" id="btn-dolar">Buscar Desactualizados</a>
    </div>
        <div class="swiper slider mb-10"> <!-- Swiper principal -->
            <div id="aumento-dolar-registros" class="swiper-wrapper"> <!-- Swiper secundario -->


                {{-- Generado con Js --}}


            </div> <!-- Swiper secundario -->
            <div class="swiper-pagination"></div> <!-- Pagination -->

            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div> <!-- Swiper principal -->

    <a class="formulario__boton" id="btn-dolar-actualizar">Actualizar todos</a>
@endsection
