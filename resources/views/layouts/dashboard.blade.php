<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')

    <meta name="description" content="Aplicación de gestión de precios e inventario">
    <link rel="icon" href="{{ asset('img/LogoSinFondo.png') }}" type="image/x-icon">

    <title>TusListas - @yield('titulo')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700;900&display=swap" rel="stylesheet">

    @vite('resources/scss/app.scss')

</head>

<body class=" bg-dashboard">
    <header class="header">
        <div class="header__contenedor">
            <a class="header__contenedor-nombre" href="{{ route('home') }}">
                <img src="{{ asset('img/LogoSinFondo.png') }}" class="header__logo" alt="Logo Tus Listas">
                <h1 class=" header__nombre">Tus Listas - {{ session('empresa')->name }}</h1>
            </a>
            @auth

                <i id="icono-menu-movil" class="header__icono-menu fa-solid fa-bars"></i>

                <nav class="header__nav">
                    @if (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'owner')
                        <a class="header__enlace header__enlace--admin @if (request()->path() === 'owner-tools') header__enlace--activo @endif
                        "
                            href="{{ route('owner-tools') }}">

                            <i class="fa-solid fa-user-gear"></i>
                            <span class="font-regular">Admin</span></a>
                    @endif
                    <a class="header__enlace font-regular @if (request()->path() === 'ayuda') header__enlace--activo @endif"
                        href="{{ route('ayuda') }}">
                        <i class="fa-solid fa-circle-info"></i>
                        Ayuda
                    </a>

                    <a class="header__enlace" href="{{ route('perfil') }}">Hola: <span
                            class="font-regular ">{{ auth()->user()->username }}</span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="header__button">Cerrar sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="header__nav">
                    <a class="header__enlace" href="{{ route('login') }}">Login</a>
                </nav>
            @endguest
        </div>
    </header>

    <main class="dashboard__grid">

        <aside class="sidebar">

            <nav class=" sidebar__nav">
                <a href="{{ route('buscador') }}" id="sidebar-buscador"
                    class="sidebar__enlace @if (request()->path() === 'buscador') activo @endif">
                    <i class="fa-solid fa-magnifying-glass sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Buscador</p>
                </a>
                <a href="{{ route('ingreso') }}"
                    class="sidebar__enlace @if (request()->path() === 'ingreso') activo @endif">
                    <i class="fa-solid fa-clipboard sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Ingreso</p>
                </a>
                <a href="{{ route('producto.create') }}" id="sidebar-new-prod"
                    class="sidebar__enlace @if (request()->path() === 'producto/nuevo-producto') activo @endif">
                    <i class="fa-solid fa-plus sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Producto</p>
                </a>

                <a href="{{ route('agenda') }}" id="sidebar-agenda"
                    class="sidebar__enlace @if (request()->path() === 'agenda') activo @endif">
                    <i class="fa-solid fa-address-card sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Agenda</p>
                </a>

                {{-- Condicional dependiendo el rol del usuario --}}
                @if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin')
                    <a href="#" class="sidebar__enlace no-owner @if (request()->path() === 'aumentos') activo @endif ">
                        <i class="fa-solid fa-dollar-sign sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Aumentos</p>
                        <i class="sidebar__alert sidebar__alert-no-owner fa-solid fa-user-gear"></i>
                    </a>
                @else
                    <a href=" {{ route('aumentos') }} "
                        class="sidebar__enlace @if (request()->path() === 'aumentos') activo @endif ">
                        <i class="fa-solid fa-dollar-sign sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Aumentos</p>
                    </a>
                @endif

                {{-- Condicional dependiendo el rol del usuario --}}
                @if (auth()->user()->user_type !== 'owner' && auth()->user()->user_type !== 'admin')
                    <a href="#"
                        class="sidebar__enlace no-owner @if (request()->path() === 'aumentos') activo @endif ">
                        <i class="fa-solid fa-chart-pie" sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Estadísticas</p>
                        <i class="sidebar__alert sidebar__alert-no-owner fa-solid fa-user-gear"></i>
                    </a>
                @else
                    <a href=" {{ route('estadisticas') }} "
                        class="sidebar__enlace @if (request()->path() === 'owner-tools/stats') activo @endif ">
                        <i class="fa-solid fa-chart-pie sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Estadísticas</p>
                    </a>
                @endif

                {{-- Solo visibles en móviles --}}
                <a href="{{ route('perfil') }}"
                    class="sidebar__enlace sidebar__enlace-movil @if (request()->path() === 'perfil') activo @endif">
                    <i class="fa-solid fa-user sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Perfil</p>
                </a>
                <a href="{{ route('ayuda') }}"
                    class="sidebar__enlace sidebar__enlace-movil @if (request()->path() === 'perfil') activo @endif">
                    <i class="fa-solid fa-circle-info sidebar__icono"></i>
                    <p class="sidebar__texto-icono">Ayuda</p>
                </a>
                @if (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'owner')
                    <a href="{{ route('owner-tools') }}"
                        class="sidebar__enlace sidebar__enlace-movil @if (request()->path() === 'owner-tools') activo @endif">
                        <i class="fa-solid fa-user-gear sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Administrador</p>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}"
                    class="sidebar__enlace sidebar__enlace-movil sidebar__enlace-movil--logout">
                    @csrf
                    <button type="submit" class="btn-reset sidebar__button-movil--logout">
                        <i class="fa-solid fa-arrow-right-from-bracket sidebar__icono"></i>
                        <p class="sidebar__texto-icono">Cerrar sesión</p>
                    </button>
                </form>

                {{-- Fin móviles --}}

            </nav>
        </aside>

        <div class="dashboard__contenedor-principal">

            @yield('tabs')

            <div class="dashboard__contenedor-secundario">

                <h2 class="dashboard__heading">
                    @yield('titulo')
                </h2>

                @yield('contenido')
            </div>
        </div>

    </main>

    @vite('resources/js/app.js')

</body>

</html>
