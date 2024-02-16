@extends('layouts.app')

@section('contenido')
    <div class="blog__contenedor">
        <h2 class="blog__show-heading">{{ $post->titulo }}</h2>

        <div class="blog__show-grid">
            <div class="blog__show-autor">
                <div class="blog__show-contenedor-logo">
                    <img src="{{ asset('img/LogoSinFondo.png') }}" class="blog__show-logo" alt="Logo Tus Listas">
                    <p class="blog__show-empresa">{{ $post->user->empresa->name }}</p>

                </div>
                <div class="blog__show-contenedor-nombre">
                    <p class="blog__show-nombre">{{ $post->user->name }}</p>
                    <p class="blog__show-fecha">{{ $post->created_at->diffForHumans() }}</p>

                </div>
            </div>

            <picture>
                <source srcset="{{ asset('img/blog/' . $post->imagen . '.avif') }}" type="image/avif">
                <source srcset="{{ asset('img/blog/' . $post->imagen . '.webp') }}" type="image/webp">
                <img loading="lazy" class="blog__show-img" src="{{ asset('img/blog/' . $post->imagen . '.jpg') }}" alt="Imagen {{ $post->titulo }}">
            </picture>
        </div>

        <p class="blog__show-contenido">{{ $post->contenido }}</p>

    </div>

    @include('home.includes.entradas')
@endsection
