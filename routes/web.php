<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/materials/{material}/download', \App\Http\Controllers\MaterialDownloadController::class)
        ->name('materials.download');
});

