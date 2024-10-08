<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\FrontendAuthController;
use App\Http\Controllers\FrontendPostController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/{slug}', [HomeController::class, 'agreements']);

#Post
Route::get('post/detail/{slug}', [FrontendPostController::class, 'show']);
#Post

#Category
Route::get('/category/{slug}', [HomeController::class, 'singleCategory']);
#Category

Route::get('/auth/login', function () {
    if(session()->get('token')){
        return redirect('/');
    }
    return view('login');
});
Route::post('/auth/login', [FrontendAuthController::class, 'loginf']);

Route::get('/auth/register', function () {
    if(session()->get('token')){
        return redirect('/');
    }
    return view('register');
});
Route::post('/auth/register', [FrontendAuthController::class, 'register']);

Route::get('/auth/logout', [FrontendAuthController::class, 'logout']);

Route::get('/auth/dashboard', [FrontendAuthController::class, 'user']);
