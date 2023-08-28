@extends('layouts.dashboard')


@section('titulo')
    {{ $producto->nombre }}
@endsection


@section('contenido')

<div class=" producto__grid">
    <div class=" producto__contenedor">
        <p><span class=" font-bold">Código: </span>{{$producto->codigo}}</p>
        <p><span class=" font-bold">Producto: </span>{{$producto->nombre}}</p>
        <p><span class=" font-bold">Fabricante: </span>{{$fabricante->nombre}}</p>
        <p><span class=" font-bold">Proveedor: </span>{{$provider->nombre}}</p>
        <p><span class=" font-bold">Categoria: </span>{{$categoria->nombre}}</p>
        <p><span class=" font-bold">Ganancia aplicada: </span>{{$producto->ganancia}}</p>
        <p><span class=" font-bold">Ganancia aplicada según: </span>{{$producto->ganancia_tipo}}</p>
        <p><span class=" font-bold">Costo sin IVA: $ </span>{{$precio->precio}}</p>
        <p><span class=" font-bold">Precio venta: $ </span>{{$producto->venta}}</p>
        <p><span class=" font-bold">Cotización compra dolar Blue: U$S </span>{{$precio->dolar}}</p>
        <p><span class=" font-bold">Modificación: </span>{{$precio->updated_at->format('j F Y, H:i')}}hs</p>
    </div>
</div>

<a href="/producto/producto-edit/{{ $producto->id }}" class="producto__card-contenedor-boton--ancho producto__boton producto__boton--verde">Modificar</a>


@endsection