<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('posts', [PostController::class, 'store']);
        Route::put('posts/{post}', [PostController::class, 'update']);
        Route::delete('posts/{post}', [PostController::class, 'destroy']);
    });

// Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::post(
    'register',
    [AuthController::class, 'register']
);

Route::post(
    'login',
    [AuthController::class, 'login']
);

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::post(
            'logout',
            [AuthController::class, 'logout']
        );

        Route::get(
            'me',
            [AuthController::class, 'me']
        );
});