
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaureateController;

Route::redirect('/', '/laureates');

Route::resource('laureates', LaureateController::class)
    ->only(['index', 'show']);
