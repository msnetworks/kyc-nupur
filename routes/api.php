<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BanksController;
use App\Http\Controllers\Api\CasesController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:api', 'token.expiration'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/getFiType', [BanksController::class, 'getFiType']);
    Route::get('/getBank', [BanksController::class, 'getBank']);
    Route::get('/getProduct/{id}', [BanksController::class, 'getProduct']);
    Route::post('/cases/create/{id}', [CasesController::class, 'storeCase']);
    Route::get('/cases/show/count/{id}', [CasesController::class, 'ShowCaseCountWise']);
    Route::get('/cases/list/{fi}/{id}/{user_id}', [CasesController::class, 'showCasebyProductId']);
    Route::get('/cases/{id}', [CasesController::class, 'showCasebyId']);
    Route::post('/cases/update', [CasesController::class, 'uploadImage']);
    Route::post('/cases/uploadSignature', [CasesController::class, 'uploadSignature']);
    Route::post('/cases/submit', [CasesController::class, 'caseSubmit']);
});
