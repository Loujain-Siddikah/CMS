<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/permissions',[PermissionController::class,'Api\PermissionController@getPermissions']);
    Route::post('/admin/add-editor',[AdminController::class,'addEditor']);
    Route::post('/admin/editors/update-editor/{id}',[AdminController::class,'updateEditor']);
    Route::delete('/admin/editors/delete-editor/{id}',[AdminController::class,'deleteEditor']);
    Route::post('/add-article',[ArticleController::class, 'add']);
    Route::post('/update-article/{id}',[ArticleController::class, 'update']);
    Route::delete('/delete-article/{id}',[ArticleController::class, 'delete']);
    Route::get('/users-articles',[ArticleController::class, 'usersArticles']);
    Route::get('/user-articles/{id}',[ArticleController::class, 'userArticles']);
});