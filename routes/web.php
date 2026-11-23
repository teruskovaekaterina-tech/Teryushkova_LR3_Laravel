<?php

use App\Http\Controllers\LaureateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/laureates');
Route::get('/laureates', [LaureateController::class, 'index'])->name('laureates.index');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::post('/laureates/{laureate}/restore', [LaureateController::class, 'restore'])
        ->withTrashed()->name('laureates.restore');
    Route::delete('/laureates/{laureate}/force', [LaureateController::class, 'forceDestroy'])
        ->withTrashed()->name('laureates.force');

    Route::resource('laureates', LaureateController::class)->except(['index', 'show']);
});

Route::get('/laureates/{laureate}', [LaureateController::class, 'show'])->name('laureates.show');

require __DIR__.'/auth.php';
