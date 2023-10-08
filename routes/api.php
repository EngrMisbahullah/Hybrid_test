<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
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

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/task',[TaskController::class,'index'])->name('task');
    Route::post('/task/create', [TaskController::class,'store'])->name('task.insert');
    Route::get('/task/{id}/show', [TaskController::class,'show'])->name('task.show');
    Route::put('/task/{id}/update', [TaskController::class,'update'])->name('task.update');
    Route::delete('/task/{id}/delete', [TaskController::class,'destroy'])->name('task.destroy');
});
