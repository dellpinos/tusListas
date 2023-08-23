@extends('layouts.dashboard')


@section('titulo')
    Listar Fabricantes - Laboratorios
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}

    @if ($fabricantes->count() > 0)
        <div class=" grid grid-cols-1 gap-6">
            @foreach ($fabricantes as $fabricante)
                <div class=" bg-gray-500 rounded shadow p-2">
                    <p><span class=" font-bold">Fabricante: </span>{{$fabricante->nombre}}</p>
                    <p><span class=" font-bold">Telefono: </span>{{$fabricante->telefono}}</p>
                    <p><span class=" font-bold">Vendedor: </span>{{$fabricante->vendedor}}</p>
                    <p><span class=" font-bold">Descripción: </span>{{$fabricante->descripcion}}</p>
                </div>
            @endforeach
        </div>

        <div class=" my-5 bg-gray-500 p-4 text-black" >
            {{ $fabricantes->links() }}
        </div>

    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron fabricantes</p>
    @endif
@endsection
