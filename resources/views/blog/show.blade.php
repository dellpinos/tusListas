@extends('layouts.app')



@section('contenido')

<div class="blog__contenedor">
    <h2 class="blog__show-heading">{{$post->titulo}}</h2>


    <div class="blog__show-grid">
        <div class="blog__show-autor">
            <img src="{{ asset('img/LogoSinFondo.png') }}" class="blog__show-logo" alt="Logo Tus Listas">
            <p class="blog__show-empresa">{{$post->user->empresa->name }}</p>
            <p class="blog__show-nombre">{{$post->user->name }}</p>
            <p class="blog__show-fecha">{{$post->created_at->diffForHumans()}}</p>
        </div>
        <img class="blog__show-img" src="{{ asset('img/blog/' . $post->imagen . '.jpg') }}" alt="Imagen {{$post->titulo}}">
    </div>
    <p class="blog__show-contenido">{{$post->contenido}}</p>
    

</div>

@include('home.includes.entradas')




@endsection