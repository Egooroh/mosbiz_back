<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TeamController;
use Doctrine\DBAL\Schema\Index;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::get('/events', [EventController::class, 'index']);

    Route::post('/feedback', [FeedbackController::class, 'store']);

    Route::get('/team', [TeamController::class, 'index']);

    Route::get('/settings', [SettingController::class, 'index']);
});