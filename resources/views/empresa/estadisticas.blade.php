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

        <div style="width: 800px;"><canvas id="stats-buscados"></canvas></div>
        <div style="width: 800px;"><canvas id="stats-stock"></canvas></div>

    </div>

@endsection()
