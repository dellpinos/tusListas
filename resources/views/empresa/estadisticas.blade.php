@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Estadísticas
@endsection

@section('contenido')


    <div class="formulario__contenedor-boton">
        <a href="{{ route('owner-tools') }}" class="categoria__boton">&laquo; Volver</a>
        {{-- <a href="{{ route('estadisticas') }}" class="categoria__boton"> Estadísticas <i class="owner__icono fa-solid fa-chart-pie"></i></a> --}}
    </div>

    <div id="contenedor-stats">

        <div class="stats__grid-cards">
            <div class="stats__contenedor-card stats__contenedor-card--green">
                <p class="stats__cards-descrip">Total Invertido en Pesos</p>
                <p class="stats__cards-numero">$ {{ $total_invertido }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--red">
                <p class="stats__cards-descrip">Descuentos Actuales</p>
                <p class="stats__cards-numero">{{ $productos_descuento }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--orange">
                <p class="stats__cards-descrip">Stock Critico (1 o menos)</p>
                <p class="stats__cards-numero">{{ $stock_critico }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--blue">
                <p class="stats__cards-descrip">Cotización Dolar Blue</p>
                <p class="stats__cards-numero">$ {{ number_format($dolar_hoy->valor, 0, ',', '.') }}</p>
                <p class="stats__cards-descrip">{{ date('d/m/y', strtotime($dolar_hoy->fecha)) }}</p>

            </div>
            <div class="stats__contenedor-card stats__contenedor-card--red">
                <p class="stats__cards-descrip">Ganancia Mes Actual</p>
                <p class="stats__cards-numero" id="stats-ganancia-mes-actual">$ ####</p>

            </div>

        </div>


        <div class="stats__contenedor-graficos-xl">
            <div class="stats__contenedor-graficos-bar">

                <div class="stats__grafico-bar"><canvas id="stats-buscados"></canvas></div>
                <div class="stats__grafico-bar"><canvas id="stats-stock"></canvas></div>


                <div class="stats__grafico-bar"><canvas id="stats-ventas"></canvas></div>
                <div class="stats__grafico-bar"><canvas id="stats-compras"></canvas></div>

                
                <div class="stats__grafico-bar"><canvas id="stats-ventas-compras"></canvas></div>
                </div>
        
                <div class="stats__contenedor-graficos-pie">
                    <div class="stats__listado">
                        <h4>Mayores Descuentos</h4>
                        <ol>
                            @foreach ($productos_principales_desc as $producto)

                            <li>{{ $producto->nombre }} _ <span>{{ $producto->precio->desc_porc}} %</span></li>
                                
                            @endforeach

                        </ol>
                    </div>
                    <div class="stats__grafico-pie">
                        <h4>Cantidad de Productos por Categoria</h4>
                        <div ><canvas id="stats-categorias"></canvas></div>
                    </div>
                    <div class="stats__grafico-pie">
                        <h4>Cantidad de Productos por Proveedor</h4>
                            
                        <div ><canvas id="stats-providers"></canvas></div>
                    </div>
                    <div class="stats__grafico-pie">
                        <h4>Cantidad de Productos por Fabricante</h4>
                        
                        <div ><canvas id="stats-fabricantes"></canvas></div>
                    </div>
                </div>

            </div>

        </div>
@endsection()
