<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TusListas - @yield('titulo')</title>

    <meta name="description" content="App de gestión de precios e inventario">

    <link rel="icon" href="{{ asset('img/LogoSinFondo.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'" />

    @vite('resources/scss/app.scss')

</head>


<body class="bg-home">
    <header class="header">
        <div class="header__contenedor">
            <a class="header__contenedor-nombre" href="{{ route('login') }}">
                <img src="{{ asset('img/LogoSinFondo.png') }}" class="header__logo" alt="Logo Tus Listas">
                <h1 class=" header__nombre">Tus Listas</h1>
            </a>
            {{-- @auth
                <nav class="header__nav">

                    <a class=" font-bold text-white mx-2" href="{{ route('buscador') }}">
                        Hola: <span class=" font-normal ">{{ auth()->user()->username }}</span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class=" hover:text-white font-bold uppercase text-gray-600">Cerrar
                            sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="header__nav">
                    <a class=" font-bold uppercase text-gray-600 hover:text-white"
                        href="{{ route('login') }}">Login</a>
                </nav>
            @endguest --}}
        </div>
    </header>

    <main class="contenedor-xl">

        <div class="home__contenedor-principal">

            <h2 class="home__heading">
                @yield('titulo')
            </h2>

            @yield('contenido')
        </div>

    </main>

    <footer class="footer__contenedor">
        <span class="footer__nombre">Martín del Pino</span> - Todos los derechos reservados {{ now()->year }}

    </footer>
    @vite('resources/js/app.js')


</body>

</html>
