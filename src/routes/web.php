<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Livewire\RagChat;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/rag', RagChat::class)->name('rag');
//Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');


Route::prefix('admin')->name('admin.')->group(function () {


    Route::middleware('guest')->group(function () {
        Route::get('/login',  [LoginController::class, 'index'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');

        Route::get('/register',  [RegisterController::class, 'index'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});
