@extends('layouts.dashboard')

@section('titulo')
    Agenda
@endsection

@section('contenido')
    <div class="agenda__grid contenedor-md">
        <div class="agenda__elemento-grid agenda__categoria">
            <h3>{{ $contador_categorias }} </h3>

            <div class="agenda__contenedor-xs">
                <i class="fa-solid fa-folder-open icono"></i>
                <p>Categorias</p>
            </div>

        </div>
        <div class="agenda__elemento-grid agenda__fabricante">
            <i class="fa-solid fa-industry sidebar__icono"></i>
            <h3>Fabricantes</h3>
            <p>{{ $contador_fabricantes }}</p>
        </div>
        <div class="agenda__elemento-grid agenda__provider">
            <i class="fa-solid fa-shop sidebar__icono"></i>
            <h3>Proveedores</h3>
            <p>{{ $contador_providers }}</p>
        </div>
        <div class="agenda__elemento-grid agenda__producto">
            <i class="fa-solid fa-boxes-stacked sidebar__icono"></i>
            <h3>Productos</h3>
            <p>{{ $contador_productos }}</p>

        </div>
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



--}}
