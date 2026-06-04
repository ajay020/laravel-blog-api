<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;

// Posts Routes
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('posts', [PostController::class, 'store']);
        Route::put('posts/{post}', [PostController::class, 'update']);
        Route::delete('posts/{post}', [PostController::class, 'destroy']);
    });

// Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');


// Comments Routes
Route::get(
    'posts/{post}/comments',
    [CommentController::class, 'index']
);

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::post(
            'posts/{post}/comments',
            [CommentController::class, 'store']
        );
    });

// Authentication Routes
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