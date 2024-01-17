<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TusListas @yield('titulo')</title>

    <meta name="description" content="Aplicación de gestión de precios e inventario">

    <link rel="icon" href="{{ asset('img/LogoSinFondo.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">

    @vite('resources/scss/app.scss')

</head>


<body class="bg-home relative">
    <header class="header header-home">
        <div class="header__contact" id="header-home-contact">
            <div class="header__contact-contenedor">
                <a href="{{ route('login') }}" class="header__contact-enlace">Login</a>
                <a href="#" class="header__contact-enlace">Contacto</a>
            </div>
        </div>
        <div class="header__contenedor">
            <a class="header__contenedor-nombre" href="{{ route('home') }}">
                <img src="{{ asset('img/LogoSinFondo.png') }}" class="header__logo" alt="Logo Tus Listas">
                <h1 class=" header__nombre">Tus Listas</h1>
            </a>
            <div class="btn-slide__contenedor">
                <a href="{{ route('register')}}" class="btn-slide__btn">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Crear Cuenta</p>
                </a>
            </div>
        </div>
    </header>

    <main class="home__contenedor-xl">

        @yield('contenido')

    </main>

    <footer class="footer__contenedor">
        <div class="footer__grid">

            <a href="{{route('blog')}}" target="_blank">Blog</a>
            <a href="#" target="_blank">Contacto</a>
            <a href="#" target="_blank">Fuente Imagenes</a>
            <a href="https://www.linkedin.com/in/martin-del-pino/" target="_blank">LinkedIn</a>
            <a href="https://tuslistas.dellpinos.com/ayuda/documentacion" target="_blank">Info</a>

        </div>
        <a class="footer__nombre" href="https://dellpinos.com/" target="_blank"><span >Martín del Pino</span> - &copy; Todos los derechos reservados {{ now()->year }}</a>
        
    </footer>
    @vite('resources/js/app.js')

</body>

</html>
