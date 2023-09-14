@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Proveedores
@endsection


@section('contenido')

<div class="formulario__contenedor-boton">
    <a href="{{ route('provider.create') }}" class="provider__boton">Crear Proveedor</a>
    <div class="formulario__contenedor-busqueda">
        <i class="formulario__icono-busqueda fa-solid fa-magnifying-glass"></i>
        <input class="formulario__campo-busqueda" id="provider-formulario" type="text">
    </div>
</div>

<div class="swiper slider mb-10"> <!-- Swiper principal -->
    <div class="swiper-wrapper" id="providers-registros"> <!-- Swiper secundario -->

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











{{-- <<<<<<<<<<< --}}

@section('contenido')

    <div class="formulario__contenedor-boton">
        <a href="{{ route('provider.create') }}" class="provider__boton">Crear Proveedor</a>
        <a href="#" class="provider__boton">Buscar Proveedor</a>
    </div>

    @if ($providers->count() > 0)
        <div class="swiper slider mb-10"> <!-- Swiper principal -->
            <div class="swiper-wrapper"> <!-- Swiper secundario -->

                @foreach ($providers as $provider)
                    <div class=" provider__contenedor swiper-slide">




                        <h3>{{ $provider->nombre }}</h3>
                        <p><span class="font-bold">Ganancia: </span><span class="provider__ganancia">{{ $provider->ganancia }}</span></p>
                        <p><span class=" font-bold">Email: </span>{{ $provider->email }}</p>
                        <p><span class=" font-bold">Teléfono: </span>{{ $provider->telefono }}</p>
                        <p><span class=" font-bold">Vendedor: </span>{{ $provider->vendedor }}</p>
                        <p><span class=" font-bold">Web: </span>{{ $provider->web }}</p>




                        <div class="formulario__contenedor-boton formulario__contenedor-boton--sm">
                            <a class="provider__boton provider__boton--modificar"
                                href="{{ route('provider.edit', $provider) }}">Ver / Editar</a>
                            <form action="{{ route('provider.destroy', $provider) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="provider__boton provider__boton--eliminar">Eliminar</button>
                            </form>


                            
                        </div>
                    </div>
                @endforeach

            </div> <!-- Swiper secundario -->

            <div class="swiper-pagination"></div> <!-- Pagination -->

            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div> <!-- Swiper principal -->
    @else
        <p class="mensaje__vacio">No se encontraron proveedores</p>
    @endif
@endsection
