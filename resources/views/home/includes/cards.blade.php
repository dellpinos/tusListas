<div class="cards__contenedor" id="cards-contenedor">

    <div class="cards__texto">
        <h2 class="cards__heading">Manejo de Inventario y mucho más</h2>
        <p class="cards__descripcion">Descubre la potencia de la aplicación con un sólido manejo de inventario y una variedad de funciones que optimizarán tu experiencia.</p>
    </div>

    <div class="cards__grid">
        <div class="cards__card">
            <div>
                <img class="cards__img" src="{{ asset('img/svg/busqueda.svg') }}" alt="Imagen Buscador" height="100">
                <h4 class="cards__heading-xs">Buscador Avanzado</h4>
                <p class="cards__text-xs">El buscador te permite listar todos los productos, filtrarlos y ordenarlos para facilitar la búsqueda.</p>
            </div>
            <div class="btn-slide__contenedor">
                <a href="{{ route('documentacion', '#docu-heading-buscador') }}" class="btn-slide__btn" target="_blank">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Busqueda</p>
                </a>
            </div>
        </div>

        <div class="cards__card">
            <div>
                <img class="cards__img" src="{{ asset('img/svg/cloud.svg') }}" alt="Imagen Buscador" height="100">
                <h4 class="cards__heading-xs">Tus Productos en la Nube</h4>
                <p class="cards__text-xs">Tu agenda y productos se almacenan en la nube; puedes acceder a todo tu stock desde cualquier parte del mundo. Todas tus herramientas, donde las necesites.</p>
            </div>
            <div class="btn-slide__contenedor">
                <a href="{{ route('documentacion', '#docu-heading-ingreso') }}" class="btn-slide__btn">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Nube</p>
                </a>
            </div>
        </div>

        <div class="cards__card">
            <div>
                <img class="cards__img" src="{{ asset('img/svg/personas.svg') }}" alt="Imagen Buscador" height="100">
                <h4 class="cards__heading-xs">Tu Grupo de Trabajo</h4>
                <p class="cards__text-xs">Una vez creada tu empresa, puedes incluir a tus empleados o a tu equipo de trabajo para trabajar en tu inventario.</p>
            </div>
            
            <div class="btn-slide__contenedor">
                <a href="{{ route('documentacion', '#docu-heading-propietario') }}" class="btn-slide__btn">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Grupo</p>
                </a>
            </div>
        </div>

        <div class="cards__card">
            <div>
                <img class="cards__img" src="{{ asset('img/svg/mobile-store.svg') }}" alt="Imagen Buscador" height="100">
                <h4 class="cards__heading-xs">Todos tus Dispositivos</h4>
                <p class="cards__text-xs">¡TusListas está lista para usar en dispositivos móviles sin necesidad de descargas! Simplemente ábrelo en tu navegador favorito y comienza a disfrutar.</p>
            </div>
            <div class="btn-slide__contenedor">
                <a href="{{ route('documentacion', '#docu-heading-responsive') }}" class="btn-slide__btn">
                    <i class="btn-slide__icon fa-solid fa-circle-arrow-right"></i>
                    <p class="btn-slide__txt">Responsive</p>
                </a>
            </div>
        </div>

    </div>
</div>
