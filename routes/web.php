<?php

use App\Http\Controllers\APIStats;
use App\Http\Controllers\APICodigo;
use App\Http\Controllers\APIVentas;
use App\Http\Controllers\APICompras;
use App\Http\Controllers\APIAumentos;
use App\Http\Controllers\APIBuscador;
use App\Http\Controllers\APICalculos;
use App\Http\Controllers\APITutorial;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductos;
use App\Http\Controllers\APIProviders;
use App\Http\Controllers\APICategorias;
use App\Http\Controllers\APIOwnerTools;
use App\Http\Controllers\APIPendientes;
use App\Http\Controllers\APIFabricantes;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AumentoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FabricanteController;

/* Rutas Public */

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contacto
Route::get('/contacto', [HomeController::class, 'contacto'])->name('contacto');

// Fuente
Route::get('/fuentes', [HomeController::class, 'fuentes'])->name('fuentes');

// Blog
Route::get('/blog', [BlogPostController::class, 'index'])->name('blog');
Route::get('/blog/{post}', [BlogPostController::class, 'show'])->name('blog.show');

// Buscador
Route::get('/buscador', [ProductoController::class, 'index'])->name('buscador');

// Empresa
Route::get('/owner-tools', [EmpresaController::class, 'index'])->name('owner-tools');
Route::get('/owner-tools/stats', [EmpresaController::class, 'estadisticas'])->name('estadisticas');


Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');

// Productos
Route::get('/producto/nuevo-producto', [ProductoController::class, 'create'])->name('producto.create');
Route::post('/producto/producto-store', [ProductoController::class, 'store'])->name('producto.store');
Route::get('/producto/producto-show/{producto}', [ProductoController::class, 'show'])->name('producto.show');
Route::get('/producto/producto-edit/{producto}', [ProductoController::class, 'edit'])->name('producto.edit');
Route::post('/producto/producto-update', [ProductoController::class, 'update'])->name('producto.update');

// Aumentos
Route::get('/aumentos', [AumentoController::class, 'index'])->name('aumentos');
Route::get('/aumento/listado', [AumentoController::class, 'listado_aumentos'])->name('aumento.listado');
Route::get('aumento/dolar', [AumentoController::class, 'dolar_aumentos'])->name('aumento.dolar');

// Ingreso de Mercaderia
Route::get('/ingreso', [IngresoController::class, 'index'])->name('ingreso');

// Agenda
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');

// Fabricantes
Route::get('/fabricantes', [FabricanteController::class, 'index'])->name('fabricantes');
Route::get('/fabricante/nuevo-fabricante', [FabricanteController::class, 'create'])->name('fabricante.create');
Route::post('/fabricante/fabricante-store', [FabricanteController::class, 'store'])->name('fabricante.store');
Route::get('/fabricante/fabricante-edit/{fabricante}', [FabricanteController::class, 'edit'])->name('fabricante.edit');
Route::post('/fabricante/fabricante-update', [FabricanteController::class, 'update'])->name('fabricante.update');

// Provider - Proveedores
Route::get('/providers', [ProviderController::class, 'index'])->name('providers');
Route::get('/provider/nuevo-provider', [ProviderController::class, 'create'])->name('provider.create');
Route::post('/provider/provider-store', [ProviderController::class, 'store'])->name('provider.store');
Route::get('/provider/provider-edit/{provider}', [ProviderController::class, 'edit'])->name('provider.edit');
Route::post('/provider/provider-update', [ProviderController::class, 'update'])->name('provider.update');

// Categorias
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
Route::get('/categoria/nueva-categoria', [CategoriaController::class, 'create'])->name('categoria.create');
Route::post('/categoria/categoria-store', [CategoriaController::class, 'store'])->name('categoria.store');
Route::get('/categoria/categoria-edit/{categoria}', [CategoriaController::class, 'edit'])->name('categoria.edit');
Route::post('/categoria/categoria-update', [CategoriaController::class, 'update'])->name('categoria.update');

// Ayuda
Route::get('/ayuda', [AyudaController::class, 'index'])->middleware(['auth', 'verified', 'empresa.asignar'])->name('ayuda');
Route::get('/ayuda/documentacion', [AyudaController::class, 'documentacion'])->name('documentacion');


/* Tener en cuenta que Fortify crea multiples rutas para manejar la autenticación y los emails de verificación */


/* APIs */

// API buscador
Route::post('/api/buscador/todos', [APIBuscador::class, 'index']);
Route::post('/api/buscador/producto', [APIBuscador::class, 'nombre_producto']);
Route::post('/api/buscador/producto-codigo', [APIBuscador::class, 'codigo_producto']);
Route::post('/api/buscador/producto-individual', [APIBuscador::class, 'producto_individual']);
Route::get('/api/buscador/consultarCFP', [APIBuscador::class, 'consultar_CFP']);

// API OwnerTools
Route::get('/api/owner-tools/all', [APIOwnerTools::class, 'all']);
Route::get('/api/owner-tools/name', [APIOwnerTools::class, 'name']);
Route::post('/api/owner-tools/destroy', [APIOwnerTools::class, 'destroy']);
Route::post('/api/owner-tools/update', [APIOwnerTools::class, 'update']);
Route::post('/api/owner-tools/send', [APIOwnerTools::class, 'send']);

// API Stats
Route::get('/api/stats/buscados', [APIStats::class, 'buscados']);
Route::get('/api/stats/stock', [APIStats::class, 'stock']);

// API Calculos
Route::post('/api/calculo/ganancia', [APICalculos::class, 'calculo_ganancia']);

// API Codigo
Route::get('/api/codigo-unico', [APICodigo::class, 'generar_codigo']);

// API Aumentos
Route::post('/api/aumentos/categoria', [APIAumentos::class, 'aumento_categoria']);
Route::post('/api/aumentos/fabricante', [APIAumentos::class, 'aumento_fabricante']);
Route::post('/api/aumentos/provider', [APIAumentos::class, 'aumento_provider']);
Route::get('/api/aumentos/dolar-listado', [APIAumentos::class, 'dolar_listado']);
Route::post('/api/aumentos/dolar-busqueda', [APIAumentos::class, 'dolar_busqueda']);
Route::post('/api/aumentos/dolar-count', [APIAumentos::class, 'dolar_count']);
Route::post('/api/aumentos/dolar-update', [APIAumentos::class, 'dolar_update']);

// API Categoria
Route::get('/api/categorias/all', [APICategorias::class, 'all']);
Route::post('/api/categorias/destroy', [APICategorias::class, 'destroy']);

// API Fabricante
Route::get('/api/fabricantes/all', [APIFabricantes::class, 'all']);
Route::post('/api/fabricantes/destroy', [APIFabricantes::class, 'destroy']);

// API Provider
Route::get('/api/providers/all', [APIProviders::class, 'all']);
Route::post('/api/providers/destroy', [APIProviders::class, 'destroy']);

// API Producto
Route::get('/api/productos/all', [APIProductos::class, 'all']);
Route::post('/api/productos/destroy', [APIProductos::class, 'destroy']);
Route::post('/api/productos/update', [APIProductos::class, 'update']);

// API Pendientes
Route::get('/api/pendientes/index', [APIPendientes::class, 'index']);
Route::get('/api/pendientes/count', [APIPendientes::class, 'count']);
Route::post('/api/pendientes/create', [APIPendientes::class, 'create']);
Route::post('/api/pendientes/destroy', [APIPendientes::class, 'destroy']);

// API Tutorial
Route::get('/api/tutorial/consulta', [APITutorial::class, 'consulta']);
Route::post('/api/tutorial/modificar', [APITutorial::class, 'modificar']);
Route::post('/api/tutorial/set-lvl', [APITutorial::class, 'set_lvl']);

// API Ventas
Route::get('/api/ventas/all', [APIVentas::class, 'index']);
Route::post('/api/ventas/create', [APIVentas::class, 'nueva_venta']);

// API Compras
Route::get('/api/compras/all', [APICompras::class, 'index']);
