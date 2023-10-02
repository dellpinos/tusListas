@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('tabs')
    <div id="dashboard__contenedor-tabs" class="dashboard__contenedor-tabs">
        <div class="dashboard__tabs" id="dashboard__tabs">
            <p class="dashboard__tab" id="dashboard__tab-todos">Todos</p>
            <p class="dashboard__tab" id="dashboard__tab-producto">Producto</p>
            <p class="dashboard__tab" id="dashboard__tab-codigo">Código</p>
        </div>
        <i class="dashboard__tab-icono fa-solid fa-chevron-down"></i>
    </div>
@endsection

@section('titulo')
    Buscador
@endsection


@section('contenido')

    <div id="buscador__contenedor-principal">

    </div>

@endsection
