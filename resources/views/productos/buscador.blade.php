@extends('layouts.app')

@section('titulo')
    Buscador
@endsection


@section('contenido')

    {{-- Aqui debe haber un formulario que envie el request para la consulta, luego hacer un buscador con Js --}}
    <div class=" bg-white p-6 rounded-lg shadow-xl">
        <input type="text" id="#" name="#" placeholder="Que estas buscando?"
            class="border p-3 w-full rounded-lg">
    </div>
@endsection
