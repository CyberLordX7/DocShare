<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


require __DIR__.'/api/v1/auth.php';
require __DIR__.'/api/v1/role.php';
require __DIR__.'/api/v1/files.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
