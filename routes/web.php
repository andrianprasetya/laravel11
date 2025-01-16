<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name("home");

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'breadcrumbs' => [
            [
                'label' => 'Dashboard',
                'slug' => 'dashboard'
            ]
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['as' => 'web::admin', 'namespace' => 'Web', 'middleware' => 'auth:web', 'prefix' => 'admin'], function () {
    Route::group(['as' => '.user', 'prefix' => 'user'], function () {
        Route::group(['as' => '.session', 'prefix' => 'session'], function () {
            Route::get('/', [SessionController::class, 'index'])->name('.index');
            Route::get('/get-datatable', [SessionController::class, 'getDatatable'])->name('.getDatatable');
            Route::get('/show/{id}', [SessionController::class, 'show'])->name('.show');
        });

        Route::group(['as' => '.role', 'prefix' => 'role'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('role.index');
            Route::get('/get-datatable', [RoleController::class, 'getDatatable'])->name('role.getDatatable');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('.edit');
            Route::get('/show/{id}', [RoleController::class, 'show'])->name('.show');
            Route::delete('/delete/{id}', [RoleController::class, 'delete'])->name('.delete');
        });

        Route::get('/', [UserController::class, 'index'])->name('.index');
        Route::get('/get-datatable', [UserController::class, 'getDatatable'])->name('.getDatatable');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('.edit');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('.show');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('.delete');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
