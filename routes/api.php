<?php

declare(strict_types=1);

use App\Api\v1\Http\Controllers\{
    TranslationController,
    VariableController
};
use App\Api\v1\Http\Controllers\Auth\{
    AuthController,
    ResetPasswordController,
    RestorePasswordController
};
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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::post('password/reset', [ResetPasswordController::class, 'reset']);
        Route::post('password/restore', [RestorePasswordController::class, 'restore']);

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('password/change', [AuthController::class, 'changePassword']);
        });

        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::group(['middleware' => 'auth:api'], function () {
        //
    });

    Route::get('translations', TranslationController::class)->name('translations.index');
    Route::get('variables', [VariableController::class, 'index']);
});
