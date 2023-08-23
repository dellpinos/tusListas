<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FabricanteController;



Route::get('/', function () {
    return view('layouts/app');
});

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

// Fabricantes
Route::get('/fabricantes', [FabricanteController::class, 'index'])->name('fabricantes');
Route::get('/fabricante/nuevo-fabricante', [FabricanteController::class, 'create'])->name('fabricante.create');
Route::post('/fabricante/fabricante-store', [FabricanteController::class, 'store'])->name('fabricante.store');

// Provider - Proveedores
Route::get('/proveedores', [ProviderController::class, 'index'])->name('proveedores');
Route::get('/proveedor/nuevo-proveedor', [ProviderController::class, 'create'])->name('proveedor.create');
Route::post('/proveedor/proveedor-store', [ProviderController::class, 'store'])->name('proveedor.store');

// Categorias
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
Route::get('/categoria/nueva-categoria', [CategoriaController::class, 'create'])->name('categoria.create');
Route::post('/categoria/categoria-store', [CategoriaController::class, 'store'])->name('categoria.store');



// Prueba del buscador
// Route::post('/producto/buscador', [ProductoController::class, 'show'])->name('producto.find');
Route::get('/producto/buscador', [ProductoController::class, 'index'])->name('buscador');



