<?php

use App\Http\Controllers\Api\AskController;
use App\Http\Controllers\Api\ClassifyController;
use App\Http\Controllers\Api\ImportCopyController;
use App\Http\Controllers\Api\ImportStreamController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function () {
    Route::get('search', SearchController::class);
    Route::post('ask', AskController::class);
    Route::post('classify', ClassifyController::class);
});

Route::post('import/stream', ImportStreamController::class);
Route::post('import/copy', ImportCopyController::class);
