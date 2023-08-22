@extends('layouts.app')


@section('titulo')
    Listar Proveedores
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}

    @if ($proveedores->count() > 0)
        <div class=" grid grid-cols-1 gap-6">
            @foreach ($proveedores as $proveedor)
                <div class=" bg-gray-500 rounded shadow p-2">
                    <p><span class=" font-bold">Proveedor: </span>{{$proveedor->nombre}}</p>
                    <p><span class=" font-bold">Email: </span>{{$proveedor->email}}</p>
                    <p><span class=" font-bold">Telefono: </span>{{$proveedor->telefono}}</p>
                    <p><span class=" font-bold">Vendedor: </span>{{$proveedor->vendedor}}</p>
                    <p><span class=" font-bold">Web: </span>{{$proveedor->web}}</p>
                    <p><span class=" font-bold">Ganancia: </span>{{$proveedor->ganancia}}</p>
                </div>
            @endforeach
        </div>

        <div class=" my-5 bg-gray-500 p-4 text-black" >
            {{ $proveedores->links() }}
        </div>

    @else
        <p class=" text-gray-600 uppercase text-sm text-center font-bold">No se encontraron proveedores</p>
    @endif
@endsection