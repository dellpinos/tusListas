@extends('layouts.dashboard')


@section('titulo')
    Listar Proveedores
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}
    <div class="provider__contenedor-boton" >
        <a href="{{ route('proveedor.create') }}" class="provider__boton">Crear Proveedor</a>
        <a href="#" class="provider__boton">Buscar Proveedor</a>
    </div>


    @if ($proveedores->count() > 0)
        <div class=" provider__grid">
            @foreach ($proveedores as $proveedor)
                <div class=" provider__contenedor">
                    <p><span class=" font-bold">Proveedor: </span>{{$proveedor->nombre}}</p>
                    <p><span class=" font-bold">Email: </span>{{$proveedor->email}}</p>
                    <p><span class=" font-bold">Telefono: </span>{{$proveedor->telefono}}</p>
                    <p><span class=" font-bold">Vendedor: </span>{{$proveedor->vendedor}}</p>
                    <p><span class=" font-bold">Web: </span>{{$proveedor->web}}</p>
                    <p><span class=" font-bold">Ganancia: </span>{{$proveedor->ganancia}}</p>

                    <div class="provider__contenedor-boton provider__contenedor-boton--sm">
                        <a class="provider__boton provider__boton--modificar" href="#">Ver / Editar</a>
                        <form action="#" method="POST">
                            <button type="submit" class="provider__boton provider__boton--eliminar">Eliminar</button>
                        </form>
                    </div>
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