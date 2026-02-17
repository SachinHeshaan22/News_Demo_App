<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    // News API routes
    Route::apiResource('news', NewsController::class);
    
    // Additional routes for publish/unpublish actions
    Route::patch('news/{id}/publish', [NewsController::class, 'publish']);
    Route::patch('news/{id}/unpublish', [NewsController::class, 'unpublish']);
});