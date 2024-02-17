@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Estadísticas
@endsection

@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('owner-tools') }}" class="categoria__boton">&laquo; Volver</a>
        {{-- <a href="{{ route('estadisticas') }}" class="categoria__boton"> Estadísticas <i class="owner__icono fa-solid fa-chart-pie"></i></a> --}}
    </div>

    <div id="contenedor-stats">

        <div class="stats__grid-cards">
            <div class="stats__contenedor-card stats__contenedor-card--green">
                <p class="stats__cards-descrip">Total Invertido en Pesos</p>
                <p class="stats__cards-numero">$ {{ $total_invertido }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--red">
                <p class="stats__cards-descrip">Descuentos Actuales</p>
                <p class="stats__cards-numero">{{ $productos_descuento }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--orange">
                <p class="stats__cards-descrip">Stock Critico (1 o menos)</p>
                <p class="stats__cards-numero">{{ $stock_critico }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--blue">
                <p class="stats__cards-descrip">Cotización Dolar Blue</p>
                <p class="stats__cards-numero">$ {{ number_format($dolar_hoy->valor, 0, ',', '.') }}</p>
                <p class="stats__cards-descrip">{{ date("d/m/y", strtotime($dolar_hoy->fecha))}}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--red">
                <p class="stats__cards-descrip">Ganancia Mes Actual</p>
                <p class="stats__cards-numero">$ ####</p>

            </div>

        </div>





        <div style="width: 800px;"><canvas id="stats-buscados"></canvas></div>
        <div style="width: 800px;"><canvas id="stats-stock"></canvas></div>

    </div>
@endsection()
