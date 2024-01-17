@extends('layouts.app')



@section('contenido')

<div class="blog__contenedor">
    <h2>Blog</h2>
    <div class="blog__grid">

        @foreach ($posts as $post)
        <a class="blog__entrada" href="{{route('blog.show', $post->id)}}">
            <img src="{{ asset('img/blog/thumb/' . $post->imagen . '_thumb.jpg') }}" alt="Imagen {{$post->titulo}}" width="300">
            <div class="blog__entrada-texto">
                <h3>{{$post->titulo}}</h3>
                <p>{{$post->contenido}}</p>
            </div>
            <p class="blog__entrada-seguir">Seguir leyendo</p>
        </a>
        @endforeach
    </div>

</div>



@endsection
