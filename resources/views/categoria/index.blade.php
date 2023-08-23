@extends('layouts.dashboard')


@section('titulo')
    Listar Categorias
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}

    @if ($categorias->count() > 0)
        <div class=" grid grid-cols-1 gap-6">
            @foreach ($categorias as $categoria)
                <div class=" bg-gray-500 rounded shadow p-2">
                    <p><span class=" font-bold">Categoria: </span>{{$categoria->nombre}}</p>
                    <p><span class=" font-bold">Ganancia: </span>{{$categoria->ganancia}}</p>
                </div>
            @endforeach
        </div>

        <div class=" my-5 bg-gray-500 p-4 text-black" >
            {{ $categorias->links() }}
        </div>

    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron fabricantes</p>
    @endif
@endsection
