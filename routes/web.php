<?php

use App\Http\Controllers\APIBuscador;
use App\Http\Controllers\APICalculos;
use App\Http\Controllers\APICodigo;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
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
Route::delete('producto/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy');

// Fabricantes
Route::get('/fabricantes', [FabricanteController::class, 'index'])->name('fabricantes');
Route::get('/fabricante/nuevo-fabricante', [FabricanteController::class, 'create'])->name('fabricante.create');
Route::post('/fabricante/fabricante-store', [FabricanteController::class, 'store'])->name('fabricante.store');
Route::get('/fabricante/fabricante-edit/{fabricante}', [FabricanteController::class, 'edit'])->name('fabricante.edit');
Route::post('/fabricante/fabricante-update', [FabricanteController::class, 'update'])->name('fabricante.update');
Route::delete('fabricante/{fabricante}', [FabricanteController::class, 'destroy'])->name('fabricante.destroy');

// Provider - Proveedores
Route::get('/providers', [ProviderController::class, 'index'])->name('providers');
Route::get('/provider/nuevo-provider', [ProviderController::class, 'create'])->name('provider.create');
Route::post('/provider/provider-store', [ProviderController::class, 'store'])->name('provider.store');
Route::get('/provider/provider-edit/{provider}', [ProviderController::class, 'edit'])->name('provider.edit');
Route::post('/provider/provider-update', [ProviderController::class, 'update'])->name('provider.update');
Route::delete('provider/{provider}', [ProviderController::class, 'destroy'])->name('provider.destroy');

// Categorias
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
Route::get('/categoria/nueva-categoria', [CategoriaController::class, 'create'])->name('categoria.create');
Route::post('/categoria/categoria-store', [CategoriaController::class, 'store'])->name('categoria.store');
Route::get('/categoria/categoria-edit/{categoria}', [CategoriaController::class, 'edit'])->name('categoria.edit');
Route::post('/categoria/categoria-update', [CategoriaController::class, 'update'])->name('categoria.update');
Route::delete('categoria/{categoria}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');


/* APIs */

// API buscador
Route::post('/api/buscador/producto', [APIBuscador::class, 'nombre_producto']);
Route::post('/api/buscador/producto-codigo', [APIBuscador::class, 'codigo_producto']);
Route::post('/api/buscador/producto-individual', [APIBuscador::class, 'producto_individual']);

// API Calculos
Route::post('/api/calculo/ganancia', [APICalculos::class, 'calculo_ganancia']);

// API Codigo
Route::get('/api/codigo-unico', [APICodigo::class, 'generar_codigo']);








