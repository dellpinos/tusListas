@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Buscador
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



@section('contenido')

{{-- <div class="buscador-listado">
    <div class="buscador-listado__grid">
        
        <select name="" id="" class="buscador-listado__dropdown">
            <option value="" selected>-- Categorias --</option>
            <option value="1">Categoria 1</option>
            <option value="2">Categoria 2</option>
            <option value=3">Categoria 3</option>
        </select>
        <select name="" id="" class="buscador-listado__dropdown">
            <option value="" selected>-- Fabricantes --</option>
            <option value="1">Fabricante 1</option>
            <option value="2">Fabricante 2</option>
            <option value=3">Fabricante 3</option>
        </select>
        <select name="" id="" class="buscador-listado__dropdown">
            <option value="" selected>-- Proveedores --</option>
            <option value="1">Proveedor 1</option>
            <option value="2">Proveedor 2</option>
            <option value=3">Proveedor 3</option>
        </select>
        <input type="text" class="buscador-listado__input" placeholder="Nombre del producto">
    </div>
</div> --}}
    <div id="buscador__contenedor-principal">

    </div>

@endsection
