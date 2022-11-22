<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BoekController;
use App\Http\Controllers\BeheerController;
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
/*LoginController*/
Route::get('/', [LoginController::class,'index']);
Route::get('/login', [LoginController::class,'login']);
Route::post('/login-user',[LoginController::class,'loginUser'])->name('login-user');
Route::get('/registration', [LoginController::class,'registration']);
Route::post('/register-user',[LoginController::class, 'registerUser'])->name('register-user');
Route::get('/logout',[LoginController::class,'logout']);

/*BeheerController*/
Route::get('/beheer', [BeheerController::class,'beheer'])->middleware('CheckRol:admin');
Route::get('/viewuser/{id}', [BeheerController::class,'viewuser'])->middleware('CheckRol:admin');

Route::get('/newgebruiker', [BeheerController::class,'newgebruiker'])->middleware('CheckRol:admin');
Route::post('/new', [BeheerController::class,'new'])->middleware('CheckRol:admin');

Route::get('/editgebruiker', [BeheerController::class,'editgebruiker'])->middleware('CheckRol:admin');
Route::get('/edituser/{id}', [BeheerController::class,'edituser'])->middleware('CheckRol:admin');
Route::post('/edit', [BeheerController::class,'edit'])->middleware('CheckRol:admin');

Route::get('/deletegebruiker', [BeheerController::class,'deletegebruiker'])->middleware('CheckRol:admin');
Route::get('/deleteuser/{id}', [BeheerController::class,'deleteuser'])->middleware('CheckRol:admin');
Route::post('/delete',[BeheerController::class, 'delete'])->middleware('CheckRol:admin');;

Route::get('/abbonementen', [BeheerController::class,'abbonement'])->middleware('CheckRol:admin');
Route::get('/editabbonement/{id}', [BeheerController::class,'editabbonement'])->middleware('CheckRol:admin');
Route::post('/updateabbonement', [BeheerController::class,'updateabbonement'])->middleware('CheckRol:admin');
Route::get('/addabbonement', [BeheerController::class,'addabbonement'])->middleware('CheckRol:admin');
Route::post('/newabbonement', [BeheerController::class,'newabbonement'])->middleware('CheckRol:admin');
Route::get('/deleteabbonement', [BeheerController::class,'deleteabbonement'])->middleware('CheckRol:admin');
Route::get('/delabbonement/{id}', [BeheerController::class,'delabbonement'])->middleware('CheckRol:admin');

Route::get('/meldingen', [BeheerController::class,'meldingen'])->middleware('CheckRol:admin');
Route::get('/addmelding', [BeheerController::class,'addmelding'])->middleware('CheckRol:admin');
Route::post('/newmelding/', [BeheerController::class,'newmelding'])->middleware('CheckRol:admin');
Route::get('/editmelding/{id}', [BeheerController::class,'editmelding'])->middleware('CheckRol:admin');
Route::post('/updatemelding', [BeheerController::class,'updatemelding'])->middleware('CheckRol:admin');
Route::get('/deletemelding', [BeheerController::class,'deletemelding'])->middleware('CheckRol:admin');
Route::get('/deletemelding/{id}', [BeheerController::class,'meldingdelete'])->middleware('CheckRol:admin');

/*BoekController*/
Route::get('/boeken', [BoekController::class,'boeken']);
Route::get('/boek/{id}',[BoekController::class,'view_boek']);
Route::get('/boek/reserveren/{id}',[BoekController::class,'reservate_boek'])->middleware('CheckRol:reservate_book');
Route::post('/boek/reserverenklant',[BoekController::class,'reservate_book_client'])->middleware('CheckRol:reservate_book_client');
Route::post('/boek/uitlenen',[BoekController::class,'uitlenen'])->middleware('CheckRol:lent_book');
Route::get('/terugbrengen',[BoekController::class,'boek_terug_brengen'])->middleware('CheckRol:return_book');

/*AccountController*/
Route::get('/account', [AccountController::class,'account'])->middleware('CheckRol:view_account');
Route::get('/verlengen/{id}', [AccountController::class,'verlengen'])->middleware('CheckRol:view_account');
Route::get('/cancel/{id}', [AccountController::class,'cancel'])->middleware('CheckRol:view_account');
Route::get('/abbonementwijzigen',[AccountController::class,'abbonementwijzigen'])->middleware('CheckRol:view_subscriptions');
Route::get('/afsluiten',[AccountController::class,'afsluiten'])->middleware('CheckRol:view_subscriptions');
Route::post('/upload-file', [AccountController::class, 'fileUpload'])->name('fileUpload');





