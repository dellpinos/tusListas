@extends('layouts.dashboard')


@section('titulo')
    Categorias
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}
    <div class="categoria__contenedor-boton" >
        <a href="{{ route('categoria.create') }}" class="categoria__boton">Crear Categoria</a>
        <a href="#" class="categoria__boton">Buscar Categoria</a>
    </div>

    @if ($categorias->count() > 0)
        <div class=" categoria__grid">
            @foreach ($categorias as $categoria)
                <div class="categoria__contenedor">
                    <p><span class=" font-bold">Categoria: </span>{{$categoria->nombre}}</p>
                    <p><span class=" font-bold">Ganancia: </span>{{$categoria->ganancia}}</p>

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
        </div>

        <div class=" my-5 bg-gray-500 p-4 text-black" >
            {{ $categorias->links() }}
        </div>

    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron categorias</p>
    @endif
@endsection
