@extends('layouts.app')


@section('titulo')
    {{ $producto->nombre }}
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}

    <div class=" grid grid-cols-1 gap-6">
        <div class=" bg-gray-500 rounded shadow p-2">
            <p><span class=" font-bold">Producto: </span>{{$producto->nombre}}</p>
            <p><span class=" font-bold">Fabricante: </span>{{$fabricante->nombre}}</p>
            <p><span class=" font-bold">Proveedor: </span>{{$provider->nombre}}</p>
            <p><span class=" font-bold">Categoria: </span>{{$categoria->nombre}}</p>

            <p><span class=" font-bold">Costo: </span>{{$precio->precio}}</p>
            <p><span class=" font-bold">Dolar: </span>{{$precio->dolar}}</p>
            <p><span class=" font-bold">Modificación: </span>{{$precio->updated_at}}</p>


        </div>

    </div>



{{--     @if ($fabricantes->count() > 0)


    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron fabricantes</p>
    @endif --}}
@endsection