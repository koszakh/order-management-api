<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\ScopeController;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('partnership');
    });

    Route::group(['prefix' => '/orders'], function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::post('/{order}/assign-worker', [OrderController::class, 'assignWorker']);
        Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
    });

    Route::get('/workers', [WorkerController::class, 'index']);
});

Route::group(['prefix' => 'passport', 'as' => 'passport.'], function () {
    Route::post('token', [AccessTokenController::class, 'issueToken'])->name('token');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('tokens', [AuthorizedAccessTokenController::class, 'forUser'])->name('tokens.index');
        Route::delete('tokens/{token_id}', [AuthorizedAccessTokenController::class, 'destroy'])->name('tokens.destroy');

        Route::get('clients', [ClientController::class, 'forUser'])->name('clients.index');
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
        Route::put('clients/{client_id}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('clients/{client_id}', [ClientController::class, 'destroy'])->name('clients.destroy');

        Route::get('scopes', [ScopeController::class, 'all'])->name('scopes.index');

        Route::get('personal-access-tokens', [PersonalAccessTokenController::class, 'forUser'])->name('personal.tokens.index');
        Route::post('personal-access-tokens', [PersonalAccessTokenController::class, 'store'])->name('personal.tokens.store');
        Route::delete('personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy'])->name('personal.tokens.destroy');

    });
});

Route::middleware('auth:api')->post('/logout', function (Request $request) {
    $request->user()->token()->revoke();
    return response()->json(['message' => 'Successful logout']);
});

Route::get('/greeting', function () {
    return 'Hello World';
});