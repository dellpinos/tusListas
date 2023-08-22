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
            <p><span class=" font-bold">Costo sin IVA: $ </span>{{$precio->precio}}</p>

            <p><span class=" font-bold">Prueba: $ </span>{{$precio->codigo}}</p>

            <p><span class=" font-bold">Cotización compra dolar Blue: U$S </span>{{$precio->dolar}}</p>
            <p><span class=" font-bold">Modificación: </span>{{$precio->updated_at->format('j F Y, H:i')}}hs</p>

        </div>

    </div>



{{--     @if ($fabricantes->count() > 0)


    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron fabricantes</p>
    @endif --}}
@endsection