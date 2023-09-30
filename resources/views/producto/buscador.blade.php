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
            <p class="dashboard__tab" id="dashboard__tab-categoria">Categoria</p>
            <p class="dashboard__tab" id="dashboard__tab-fabricante">Fabricante</p>
            <p class="dashboard__tab" id="dashboard__tab-provider">Proveedor</p>
        </div>
        <i class="dashboard__tab-icono fa-solid fa-chevron-down"></i>
    </div>
@endsection

@section('titulo')
    Buscador
@endsection




@section('contenido')
    {{-- @if (session('mensaje'))
        <p class="mensaje__error">{{ session('mensaje') }}</p>
    @endif --}}
    <div id="buscador__contenedor-principal">

        {{-- <div id="contenedor-input" class="formulario__contenedor-busqueda buscador__input relative">
            <i class="formulario__icono-busqueda fa-solid fa-magnifying-glass"></i>
            <input class="formulario__campo-busqueda" id="producto-nombre-falso" type="text"
                placeholder="Nombre del producto">
        </div>
    
        <div class="" id="card-producto">
            {{-- Este contenido se genera con Js --}}
            {{-- <p class="mensaje__info">No se ha realizado una busqueda</p>
        </div> --}}


    </div>





@endsection
