@extends('layouts.dashboard')


@section('titulo')
    Proveedores
@endsection


@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}
    <div class="provider__contenedor-boton" >
        <a href="{{ route('proveedor.create') }}" class="provider__boton">Crear Proveedor</a>
        <a href="#" class="provider__boton">Buscar Proveedor</a>
    </div>


    {{-- No esta pasando la instancia de proveedor adecuadamento, provablemente un problema con el nombre --}}


    @if ($proveedores->count() > 0)
        <div class=" provider__grid">
            @foreach ($proveedores as $provider)
                <div class=" provider__contenedor">
                    <p><span class=" font-bold">provider: </span>{{$provider->nombre}}</p>
                    <p><span class=" font-bold">Email: </span>{{$provider->email}}</p>
                    <p><span class=" font-bold">Telefono: </span>{{$provider->telefono}}</p>
                    <p><span class=" font-bold">Vendedor: </span>{{$provider->vendedor}}</p>
                    <p><span class=" font-bold">Web: </span>{{$provider->web}}</p>
                    <p><span class=" font-bold">Ganancia: </span>{{$provider->ganancia}}</p>

                    <div class="provider__contenedor-boton provider__contenedor-boton--sm">
                        <a class="provider__boton provider__boton--modificar" href="{{ route('proveedor.edit', $provider) }}">Ver / Editar</a>
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