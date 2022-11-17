<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BoekController;
use App\Http\Controllers\GebruikerController;
use App\Http\Controllers\AccountController;

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

Route::get('/', [LoginController::class,'index']);
Route::get('/login', [LoginController::class,'login']);
Route::post('/login-user',[LoginController::class,'loginUser'])->name('login-user');
Route::get('/registration', [LoginController::class,'registration']);
Route::post('/register-user',[LoginController::class, 'registerUser'])->name('register-user');
Route::get('/logout',[LoginController::class,'logout']);

Route::get('/gebruikers', [GebruikerController::class,'gebruikers'])->middleware('CheckRol:view_users');

Route::get('/boeken', [BoekController::class,'boeken']);
Route::get('/boek/{id}',[BoekController::class,'view_boek']);
Route::get('/boek/reserveren/{id}',[BoekController::class,'reservate_boek']);

Route::get('/account', [AccountController::class,'account'])->middleware('CheckRol:view_account');




