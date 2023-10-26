@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Agenda
@endsection

@section('contenido')
    <div class="agenda__grid contenedor-md">

        <a href="{{ route('categorias') }}" class="agenda__elemento-grid">
            <div class="agenda__contenedor-xs">
                <i class="fa-solid fa-folder-open agenda__icono"></i>
                <p class="agenda__text"><span class="agenda__contador">{{ $contador_categorias }} </span>Categorias</p>
            </div>
        </a>

        <a href="{{ route('fabricantes') }}" class="agenda__elemento-grid">
            <div class="agenda__contenedor-xs">
                <i class="fa-solid fa-industry agenda__icono"></i>
                <p class="agenda__text"><span class="agenda__contador">{{ $contador_fabricantes }} </span>Fabricantes</p>


            </div>
        </a>


        <a href="{{ route('providers') }}" class="agenda__elemento-grid">
            <div class="agenda__contenedor-xs">
                <i class="fa-solid fa-shop agenda__icono"></i>
                <p class="agenda__text"><span class="agenda__contador">{{ $contador_providers }} </span>Proveedores</p>


            </div>
        </a>

        <a href="{{ route('buscador') }}" class="agenda__elemento-grid">
            <div class="agenda__contenedor-xs">
                <i class="fa-solid fa-boxes-stacked agenda__icono"></i>
                <p class="agenda__text"><span class="agenda__contador">{{ $contador_productos }} </span>Productos</p>

            </div>
        </a>
    </div>
@endsection



{{-- Un grid con 4 elementos que corresponden a Cantidad de Categorias, Proveedores, Fabricantes.
Cada uno es un enlace a la vista ya existente.
Agregar botón de "Volver" dentro de categorias, proveedores y fabricantes
El ultimo item puede ser "Mis datos" o "Todos los productos"


                {{-- <a href="{{ route('providers') }}"
                    class="sidebar__enlace @if (request()->path() === 'providers') activo @endif">
                    <i class="fa-solid fa-shop sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Proveedores</p>
                </a>
                <a href="{{ route('categorias') }}"
                    class="sidebar__enlace @if (request()->path() === 'categorias') activo @endif">
                    <i class="fa-solid fa-folder-open sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Categorias</p>
                </a>
                <a href="{{ route('fabricantes') }}"
                    class="sidebar__enlace @if (request()->path() === 'fabricantes') activo @endif">
                    <i class="fa-solid fa-industry sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Fabricantes</p>
                </a> --}}
