<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UsersController;

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

// Route::get('/', [TaskController::class,'index'])->name('todo.home');
// Route::get('/create',[TaskController::class,'create'])->name('todo.create');
// Route::post('/create',[TaskController::class,'store'])->name('todo.store');
// Route::get('/edit/{id}',[TaskController::class,'edit'])->name('todo.edit');
// Route::post('/update',[TaskController::class,'update'])->name('todo.update');

Route::middleware('auth:sanctum')->group(function(){
    Route::delete('/delete/{id}',[TaskController::class,'delete'])->name('todos.delete');
    Route::patch('/updatestatus/{id}',[TaskController::class,'updateStatus'])->name('todos.updatestatus')->middleware('role:admin,editor');
   Route::resource('todos', TaskController::class)->except(['destroy','show']);
    Route::get('todos/logout',[AuthController::class,'logout']);
});



 
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/datatables', [UsersController::class, 'datatables'])->name('users.datatables');
//Route::get('/todos', [TaskController::class, 'index'])->name('todos.index');
Route::get('/login',[AuthController::class,'loginform'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/register',[AuthController::class,'registerform'])->name('todos.registerform');
Route::post('/register',[AuthController::class,'register'])->name('todos.register');
