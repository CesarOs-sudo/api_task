<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

});

//middleware que valida que el usuario este autenticado
Route::group([
    'middleware' => ['api', 'jwt.auth'],
    'prefix' => 'task'
], function(){
    Route::get('getAll', [TaskController::class, 'getTaskForUser']); //obtiene todas las tareas del usuario autenticado
    Route::post('create', [TaskController::class, 'createTask']); //Crea una tarea nueva relacionada al usuario autenticado
    Route::post('update/{id}', [TaskController::class, 'updateTask']); //Modifica una tarea existente relacionada con el usuario atenticado
    Route::post('delete/{id}', [TaskController::class, 'deleteTask']); //Elimina una tarea existente relacionada con el usuario autenticado
});
