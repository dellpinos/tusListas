@extends('layouts.dashboard')


@section('titulo')
    Registro de Aumentos
@endsection

@section('contenido')
<div class="formulario__contenedor-boton" >
    <a href="{{ route('aumentos') }}" class="categoria__boton">&laquo; Volver</a>
</div>

<table class="table">
    <thead class="table__thead">
        <tr>
            <th scope="col" class="table__th">Tipo de Aumento</th>
            <th scope="col" class="table__th">Nombre</th>
            <th scope="col" class="table__th">Cantidad de Precios Actualizados</th>
            <th scope="col" class="table__th">Fecha</th>
            <th scope="col" class="table__th">Porcentaje de Aumento</th>
            <th scope="col" class="table__th">Usuario</th>
        </tr>
    </thead>
    <tbody class="table__tbody">
        @foreach ($registros as $registro)
        <tr class="table__tr">
            <td class="table__td">{{ $registro->tipo }}</td>
            <td class="table__td">{{ $registro->nombre }}</td>
            <td class="table__td">{{ $registro->afectados }}</td>
            <td class="table__td">{{ $registro->created_at }}</td>
            <td class="table__td">
                {{ ((($registro->porcentaje - 1) * 100) < 1 ) ? "Varios" : ($registro->porcentaje - 1) * 100 . " %"}}
            </td>
            <td class="table__td">{{ $registro->username }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

@endsection





