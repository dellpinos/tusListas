@extends('layouts.dashboard')

@section('titulo')
    Proveedores
@endsection

@section('contenido')

    <div class="provider__contenedor-boton">
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
                        <p><span class=" font-bold">Telefono: </span>{{ $provider->telefono }}</p>
                        <p><span class=" font-bold">Vendedor: </span>{{ $provider->vendedor }}</p>
                        <p><span class=" font-bold">Web: </span>{{ $provider->web }}</p>

                        <div class="provider__contenedor-boton provider__contenedor-boton--sm">
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
