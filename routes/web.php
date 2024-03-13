<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//tampilan awal landing page
Route::get('/', function () {
    return view('home', ['title' => 'Home']);
})->name('home');

//login, tambah user, ganti password dan logout
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');
Route::get('password', [UserController::class, 'password'])->name('password');
Route::post('password', [UserController::class, 'password_action'])->name('password.action');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

//tes koneksi database
Route::get('/test-database', 'App\Http\Controllers\DatabaseTestController@testConnection');

//route admin
Route::get('/data-hima', [AdminController::class, 'dataHima'])->name('dataHima');
Route::delete('/ormawa/{id_ormawa}', [AdminController::class, 'destroy'])->name('ormawa.destroy');

