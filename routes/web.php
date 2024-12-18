<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LettersController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('getLetters', [LettersController::class, 'getLetters'])->name('getLetters');
Route::post('create', [LettersController::class, 'create'])->name('create');
Route::post('store', [LettersController::class, 'store'])->name('store');
Route::get('eletter/{id}/show', [LettersController::class, 'show'])->name('show');
Route::put('update', [LettersController::class, 'update'])->name('update');
Route::get('eletter/{id}/print', [LettersController::class, 'print'])->name('print');
Route::delete('destroy', [LettersController::class, 'destroy'])->name('destroy');