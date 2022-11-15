<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
Route::get('/login', [LoginController::class,'login'])->middleware('CheckLogin');
Route::post('/login-user',[LoginController::class,'loginUser'])->name('login-user');
Route::get('/logout',[LoginController::class,'logout']);

Route::get('/gebruikers', [LoginController::class,'gebruikers'])->middleware('CheckRol:view_users');
Route::get('/boeken', [LoginController::class,'boeken']);
Route::get('/account', [LoginController::class,'account'])->middleware('CheckRol:view_account');
Route::get('/zoeken', [LoginController::class,'zoeken']);



