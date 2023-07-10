<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard/{type}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Request Controller Routes
Route::get('/requests', [App\Http\Controllers\RequestController::class, 'index'])->name('requests');
Route::post('/requests', [App\Http\Controllers\RequestController::class, 'store']);
Route::delete('/requests/{id}', [App\Http\Controllers\RequestController::class, 'destroy']);
Route::patch('/requests/{id}', [App\Http\Controllers\RequestController::class, 'update']);

require __DIR__.'/auth.php';
