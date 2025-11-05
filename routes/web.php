<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CrudBuilderController;
use App\Http\Controllers\DynamicTableController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;

// Redirect ke halaman login jika belum login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Proteksi semua route yang perlu autentikasi
Route::middleware(['auth'])->group(function () {

    Route::get('/crud/index', [CrudBuilderController::class, 'index'])->name('crud.index');
    Route::get('/crud/list', [CrudBuilderController::class, 'list'])->name('crud.list');

    // CRUD builder routes
    Route::get('/crud/create', [CrudBuilderController::class, 'create'])->name('crud.create');
    Route::post('/crud/store', [CrudBuilderController::class, 'store'])->name('crud.store');

    Route::get('/crud/{id}/edit', [CrudBuilderController::class, 'edit'])->name('crud.edit');
    Route::put('/crud/{table}', [CrudBuilderController::class, 'update'])->name('crud.update');
    Route::delete('/crud/{id}', [CrudBuilderController::class, 'destroy'])->name('crud.destroy');

    Route::get('table/{tableName}/export', [DynamicTableController::class, 'exportExcel'])->name('table.export');

    // Route for a specific table's CRUD interface
    Route::get('/crud/{table}', [CrudBuilderController::class, 'show'])->name('crud.show');

    Route::get('/table/{tableName}', [DynamicTableController::class, 'index'])->name('table.index');
    Route::post('/table/{tableName}', [DynamicTableController::class, 'store'])->name('table.store');
    Route::get('/table/{tableName}/{id}/edit', [DynamicTableController::class, 'edit'])->name('table.edit');
    Route::put('/table/{tableName}/{id}', [DynamicTableController::class, 'update'])->name('table.update');
    Route::delete('/table/{tableName}/{id}', [DynamicTableController::class, 'destroy'])->name('table.destroy');

    // Role management routes
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
});

// Auth routes dari Breeze
require __DIR__.'/auth.php';
