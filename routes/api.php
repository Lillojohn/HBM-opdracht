<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authorization;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'createUser'])->name('createUser');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/verify/{id}', [UserController::class, 'verifyUser'])->name('verifyUser');
});

Route::prefix('tasklist')->group(function () {
    Route::get('/', [TaskListController::class, 'getTaskLists'])->name('getTaskLists');
    Route::post('/', [TaskListController::class, 'createTaskList'])->name('createTaskList');
})->middleware(Authorization::class);


Route::prefix('task')->group(function () {
    Route::get('/', [TaskController::class, 'getTasks'])->name('getTasks');
    Route::post('/', [TaskController::class, 'createTask'])->name('createTask');
    Route::get('/{id}', [TaskController::class, 'getTask'])->name('getTask');
    Route::post('/update', [TaskController::class, 'updateTask'])->name('updateTask');
    Route::post('/delete', [TaskController::class, 'deleteTask'])->name('deleteTask');
    Route::post('/search', [TaskController::class, 'searchTasks'])->name('searchTasks');
})->middleware(Authorization::class);
