<?php

use App\Http\Controllers\Panel\ClientController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\FiseController;
use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::apiResource('/productos', ProductController::class)->parameters(['productos' => 'product']);
    Route::get('/productos-ajax', [ProductController::class, 'ajax'])->name('productos.ajax');

    Route::apiResource('/clientes', ClientController::class)->parameters(['clientes' => 'client']);
    Route::get('/clientes-ajax', [ClientController::class, 'ajax'])->name('clientes.ajax');
    Route::get('/clientes-ajax-select', [ClientController::class, 'ajaxSelect'])->name('clientes.ajax-select');

    Route::apiResource('/fises', FiseController::class)->parameters(['fises' => 'fise']);
    Route::get('/fises-ajax', [FiseController::class, 'ajax'])->name('fises.ajax');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
