<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::post('/upload-test', function (Request $request) {

//     putenv('TEMP=C:\temp');
//     putenv('TMP=C:\temp');

//     return [
//         'has_file' => $request->hasFile('image'),
//         'file' => $request->file('image')?->getClientOriginalName(),
//     ];
// });

// Route::get('/temp-fix-test', function () {

//     putenv('TEMP=C:\temp');
//     putenv('TMP=C:\temp');

//     return [
//         'TEMP' => getenv('TEMP'),
//         'TMP' => getenv('TMP'),
//         'sys_temp_dir' => sys_get_temp_dir(),
//     ];
// });

// Route::get('/temp-env', function () {
//     return [
//         'TEMP' => getenv('TEMP'),
//         'TMP' => getenv('TMP'),
//     ];
// });


// Route::get('/php-config', function () {
//     return [
//         'loaded_ini' => php_ini_loaded_file(),
//         'upload_tmp_dir' => ini_get('upload_tmp_dir'),
//     ];
// });

// Route::get('/temp-test', function () {
//     return [
//         'upload_tmp_dir' => ini_get('upload_tmp_dir'),
//         'sys_temp_dir' => sys_get_temp_dir(),
//         'sys_temp_exists' => file_exists(sys_get_temp_dir()),
//         'sys_temp_writable' => is_writable(sys_get_temp_dir()),
//     ];
// });

// Route::get('/write-test', function () {

//     $path = 'C:\\temp\\test.txt';

//     file_put_contents($path, 'hello');

//     return [
//         'exists' => file_exists($path),
//         'writable' => is_writable('C:\\temp'),
//     ];
// });


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

        Route::put(
            'comments/{comment}',
            [CommentController::class, 'update']
        );

        Route::delete(
            'comments/{comment}',
            [CommentController::class, 'destroy']
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


// Tags Routes
Route::get('tags', [TagController::class, 'index']);
