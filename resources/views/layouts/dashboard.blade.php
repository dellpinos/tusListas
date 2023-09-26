<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')

    <meta name="description" content="App de gestión de precios e inventario">

    <title>TusListas - @yield('titulo')</title>
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

<body class=" bg-dashboard">
    <header class="header">
        <div class="header__contenedor">
            <a href="{{ route('buscador') }}">
                <h1 class=" header__logo">Tus Listas</h1>
            </a>
            @auth
                <nav class="header__nav">

                    @if (auth()->user()->user_type === 'admin')
                        <a class="header__enlace" href="{{ route('register') }}">User <span
                                class="font-regular">Admin</span></a>
                    @endif
                    <a class="header__enlace" href="#">Hola: <span
                            class="font-regular ">{{ auth()->user()->username }}</span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="header__button">Cerrar sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="header__nav">
                    <a cclass="header__enlace" href="{{ route('login') }}">Login</a>
                </nav>
            @endguest
        </div>
    </header>

    <main class="dashboard__grid">

        
        <aside class="sidebar">
            <nav class=" sidebar__nav">

                <a href="{{ route('buscador') }}"
                    class="sidebar__enlace @if (request()->path() === '/') activo @endif"><i
                        class="fa-solid fa-magnifying-glass sidebar__icono"></i>Buscador</a>

                <a href="{{ route('ingreso') }}"
                    class="sidebar__enlace @if (request()->path() === 'ingreso') activo @endif"><i
                        class="fa-solid fa-clipboard sidebar__icono"></i>Ingreso Mercaderia</a>
                <a href="{{ route('producto.create') }}"
                    class="sidebar__enlace @if (request()->path() === 'producto/nuevo-producto') activo @endif"><i
                        class="fa-solid fa-plus sidebar__icono"></i>Nuevo Producto</a>
                <a href="{{ route('aumentos') }}"
                    class="sidebar__enlace @if (request()->path() === 'aumentos') activo @endif""><i
                        class="fa-solid fa-dollar-sign sidebar__icono"></i>Aumentos Generales</a>

                <a href="{{ route('providers') }}"
                    class="sidebar__enlace @if (request()->path() === 'providers') activo @endif"><i
                        class="fa-solid fa-shop sidebar__icono"></i>Proveedores</a>
                <a href="{{ route('categorias') }}"
                    class="sidebar__enlace @if (request()->path() === 'categorias') activo @endif"><i
                        class="fa-solid fa-folder-open sidebar__icono"></i>Categorias</a>
                <a href="{{ route('fabricantes') }}"
                    class="sidebar__enlace @if (request()->path() === 'fabricantes') activo @endif"><i
                        class="fa-solid fa-industry sidebar__icono"></i>Fabricantes</a>

            </nav>
        </aside>

        <div class="dashboard__contenedor-principal">

            <h2 class="">
                @yield('titulo')
            </h2>

            @yield('contenido')
        </div>

    </main>

    @vite('resources/js/app.js')

</body>

</html>
