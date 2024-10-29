<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;

Route::middleware('basic.auth')->group(function () {
    Route::apiResource('items', ItemController::class);
    Route::post('items/{item}/upload-image', [ItemController::class, 'uploadImage']);
});
