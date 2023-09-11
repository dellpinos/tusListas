@extends('layouts.dashboard')

@section('titulo')
    Fabricantes - Laboratorios
@endsection

@section('contenido')
    {{-- Esto deberia ser un componente/livewire --}}
    <div class="fabricante__contenedor-boton" >
        <a href="{{ route('fabricante.create') }}" class="fabricante__boton">Crear Fabricante</a>
        <a href="#" class="fabricante__boton">Buscar Fabricante</a>
    </div>

    @if ($fabricantes->count() > 0)
        <div class="fabricante__grid">
            @foreach ($fabricantes as $fabricante)
                <div class="fabricante__contenedor">
                    <p><span class=" font-bold">Fabricante: </span>{{$fabricante->nombre}}</p>
                    <p><span class=" font-bold">Telefono: </span>{{$fabricante->telefono}}</p>
                    <p><span class=" font-bold">Vendedor: </span>{{$fabricante->vendedor}}</p>
                    <p><span class=" font-bold">Descripción: </span>{{$fabricante->descripcion}}</p>

                    <div class="fabricante__contenedor-boton fabricante__contenedor-boton--sm">
                        <a class="fabricante__boton fabricante__boton--modificar" href="{{ route('fabricante.edit', $fabricante) }}">Ver / Editar</a>
                        <form action="{{ route('fabricante.destroy', $fabricante) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="fabricante__boton fabricante__boton--eliminar">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="" >
            {{ $fabricantes->links() }}
        </div>

    @else
        <p class="mensaje__vacio">No se encontraron fabricantes</p>
    @endif
@endsection
