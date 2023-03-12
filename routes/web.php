<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Middleware\Authorization;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login');
})->name('loginPage');

Route::get('/registratie', function () {
    return view('registration');
})->name('registration');

Route::get('/takenlijst/{search?}', [TaskListController::class, 'taskListPage'])
    ->name('tasks')
    ->middleware(Authorization::class);

Route::get('/taak/{id}', [TaskController::class, 'updatePage'])
    ->name('updatePage')
    ->middleware(Authorization::class);
