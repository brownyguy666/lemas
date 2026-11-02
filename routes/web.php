<?php

use App\Http\Controllers\MaterialDownloadController;
use App\Http\Controllers\SubmissionDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/materials/{material}/download', MaterialDownloadController::class)
        ->name('materials.download');

    Route::get('/submissions/{submission}/download', SubmissionDownloadController::class)
        ->name('submissions.download');
});
