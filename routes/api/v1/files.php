<?php

use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\FileStatsController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'v1/files'
], function() {
    Route::post('/upload', [FileUploadController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('files.upload');

    Route::get('/download/{token}', [FileDownloadController::class, 'download'])
        ->name('files.download');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/upload/secure', [FileUploadController::class, 'secureStore'])
            ->middleware('throttle:60,1')
            ->name('files.upload.secure');

        Route::post('/download/{token}/auth', [FileDownloadController::class, 'authenticate'])
            ->name('files.download.auth');

        Route::get('/download/secure/{token}', [FileDownloadController::class, 'secureDownload'])
            ->name('files.download.secure');

        Route::get('/stats', [FileStatsController::class, 'stats'])
            ->middleware('can:' . \App\Enum\PermissionsEnum::ViewFileStats->value)
            ->name('files.stats');

        Route::get('/uploads', [FileUploadController::class, 'index'])
            ->name('files.uploads.list');
    });
});
