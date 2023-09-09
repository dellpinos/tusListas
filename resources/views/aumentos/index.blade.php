@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Cambiar Precios
@endsection



@section('contenido')
<div class="categoria__contenedor-boton" >
    <a href="{{ route('aumento.dolar') }}" class="categoria__boton">Aumento Dolar</a>
    <a href="{{ route('aumento.listado')}}" class="categoria__boton">Registro de Aumentos</a>
</div>
    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-categoria" class="formulario__label">Categoria</label>
                    <select class="formulario__campo" id="aumentos-categoria">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-cat" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-cat" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-cat" >Aplicar Aumento</button>
        </div>
    </div>

    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-provider" class="formulario__label">Proveedor</label>
                    <select class="formulario__campo" id="aumentos-provider">
                        <option value="" selected disabled>- Seleccionar -</option>
        
                        @foreach ($proveedores as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-pro" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-pro" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-pro" >Aplicar Aumento</button>
        </div>
    </div>

    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-fabricantes" class="formulario__label">Fabricante</label>
                    <select class="formulario__campo" id="aumentos-fabricantes">
                        <option value="" selected disabled>- Seleccionar -</option>
    
                        @foreach ($fabricantes as $fabricante)
                            <option value="{{ $fabricante->id }}">{{ $fabricante->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-fab" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-fab" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-fab" >Aplicar Aumento</button>
        </div>
    </div>


    <h3>Aumentos Individuales</h3>



    <div class="buscador__grid">
        <div class=" buscador__contenedor">

            <div class="buscador__campo-contenedor">
                <label for="producto-codigo" class="formulario__label">Código del producto</label>
                <input type="text" id="producto-codigo" name="producto-codigo" placeholder="C4GT, F320, 44G2, etc"
                    class="buscador__campo">
            </div>
            <div class="buscador__boton-contenedor">
                <a class="buscador__boton" id="btn-codigo">Buscar por Código</a>
            </div>

        </div>
        <div class=" buscador__contenedor">

            <div class="buscador__campo-contenedor">
                <label for="producto-nombre" class="formulario__label">Nombre del producto</label>
                <div id="contenedor-input" class="relative">

                    <input type="text" id="producto-nombre-falso"
                        placeholder="Pipeta power, Pecera 60x20, Collar Cuero, etc" class="buscador__campo">
                </div>

            </div>
            <div class="buscador__boton-contenedor">
                <button class="buscador__boton" id="btn-nombre">Buscar por Nombre</button>
            </div>

        </div>

    </div>
    <div class=" producto__grid producto__card-contenedor" id="card-producto">
        {{-- Este contenido se genera con Js --}}
    </div>

    </div>
@endsection
