<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TaskController::class,'index'])->name('todo.home');
Route::get('/create',[TaskController::class,'create'])->name('todo.create');
Route::post('/create',[TaskController::class,'store'])->name('todo.store');
Route::get('/edit/{id}',[TaskController::class,'edit'])->name('todo.edit');
Route::post('/update',[TaskController::class,'update'])->name('todo.update');
Route::get('/delete/{id}',[TaskController::class,'delete'])->name('todo.delete');
Route::patch('/updatestatus/{id}',[TaskController::class,'updateStatus'])->name('todo.updatestatus');