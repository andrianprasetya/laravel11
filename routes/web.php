<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\LogController;
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
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'breadcrumbs' => [
            ['name' => 'Home', 'path' => '/'],
            ['name' => 'Dashboard', 'path' => '/dashboard']
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::name('user.')->prefix('/user')->group(function () {
    Route::get('/log', function () {
        return Inertia::render('Log');
    });
    /*Route::get('/logs', [LogController::class, 'index'])->name('user.log.index');*/
});

Route::get('/new-dashboard', function () {
    return Inertia::render('NewDashboard');
})->name('new-dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
