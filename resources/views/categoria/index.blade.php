@extends('layouts.dashboard')

@section('titulo')
    Categorias
@endsection

@section('contenido')
    <div class="categoria__contenedor-boton" >
        <a href="{{ route('categoria.create') }}" class="categoria__boton">Crear Categoria</a>
        <a href="#" class="categoria__boton">Buscar Categoria</a>
    </div>

    @if ($categorias->count() > 0)

    <div class="swiper slider mb-10"> <!-- Swiper principal -->
        <div class="swiper-wrapper"> <!-- Swiper secundario -->
    
    
                @foreach ($categorias as $categoria)
                    <div class="categoria__contenedor swiper-slide">
                        <h3>{{ $categoria->nombre }}</h3>
                        <p><span class="font-bold">Ganancia: </span><span class="categoria__ganancia">{{ $categoria->ganancia }}</span></p>
    
                        <div class="categoria__contenedor-boton categoria__contenedor-boton--sm">
                            <a class="categoria__boton categoria__boton--modificar" href="{{ route('categoria.edit', $categoria) }}">Ver / Editar</a>
                            <form action="{{ route('categoria.destroy', $categoria) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="categoria__boton categoria__boton--eliminar">Eliminar</button>
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
        <p class="mensaje__vacio">No se encontraron categorias</p>
    @endif
@endsection

