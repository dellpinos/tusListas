<?php

use App\Http\Controllers\APICodigo;
use App\Http\Controllers\APIAumentos;
use App\Http\Controllers\APIBuscador;
use App\Http\Controllers\APICalculos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductos;
use App\Http\Controllers\APIProviders;
use App\Http\Controllers\APICategorias;
use App\Http\Controllers\APIFabricantes;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AumentoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FabricanteController;


// Buscador
Route::get('/', [ProductoController::class, 'index'])->name('buscador');

// Auth
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

// Registro - Rutas no visibles para los usuarios (solo el admin crea cuentas)
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

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


/* APIs */

// API buscador
Route::post('/api/buscador/producto', [APIBuscador::class, 'nombre_producto']);
Route::post('/api/buscador/producto-codigo', [APIBuscador::class, 'codigo_producto']);
Route::post('/api/buscador/producto-individual', [APIBuscador::class, 'producto_individual']);

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









