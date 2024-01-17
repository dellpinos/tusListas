<div class="entradas__contenedor">

    <div class="entradas__texto">
        <h2 class="entradas__heading">Últimos Articulos</h2>

        <div class="entradas__contenedor-btn">

            <div class="btn-slide__contenedor">
                <a href="#" class="btn-slide__btn">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Ver Todos</p>
                </a>
            </div>
        </div>
    </div>

    
    
    <div class="entradas__grid">
        @foreach ($posts as $post)
        <div class="entradas__card">
            
            <div>
                <img src="{{ asset('img/blog/thumb/' . $post->imagen . '_thumb.jpg') }}" alt="Imagen {{$post->titulo}}" width="400">
                <h4 class="entradas__heading-xs">{{$post->titulo}}</h4>
                <p class="entradas__text-xs">{{$post->contenido}}</p>
            </div>
            <div class="entradas__link">
                <a href="{{route('blog.show', $post->id)}}">Leer mas</a>
            </div>

        </div>

        @endforeach

    </div>
</div>
