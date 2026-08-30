<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\AdminContentController;
Route::prefix('v1')->group(function(){
 Route::prefix('public')->group(function(){Route::get('home',[PublicController::class,'home']);Route::get('settings',[PublicController::class,'settings']);Route::get('{type}',[PublicController::class,'index']);Route::get('{type}/{slug}',[PublicController::class,'show']);});
 Route::post('auth/login',[AuthController::class,'login'])->middleware('throttle:5,1');
 Route::middleware('auth:sanctum')->group(function(){Route::get('auth/me',[AuthController::class,'me']);Route::post('auth/logout',[AuthController::class,'logout']);Route::prefix('admin')->group(function(){Route::post('upload/image',[AdminContentController::class,'uploadImage']);Route::get('settings',[AdminContentController::class,'settings']);Route::put('settings',[AdminContentController::class,'updateSettings']);Route::get('activity',[AdminContentController::class,'activity']);Route::get('{type}',[AdminContentController::class,'index']);Route::get('{type}/{item}',[AdminContentController::class,'show']);Route::post('{type}',[AdminContentController::class,'store']);Route::put('{type}/reorder',[AdminContentController::class,'reorder']);Route::put('{type}/{item}',[AdminContentController::class,'update']);Route::delete('{type}/{item}',[AdminContentController::class,'destroy']);});});
});
