<?php

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// public api routes
if (config('settings.allow_public_registration')) {
    Route::post('user/register', 'Api\AuthController@register')->name('user.register');
}

// guest-only api routes
Route::middleware(['guest:api'])->as('api.')->group(function () {
    Route::post('auth/login', 'Api\AuthController@login')->name('auth.login');
    Route::post('auth/forgot-password', 'Api\AuthController@forgotPassword')->name('auth.forgot-password');
});

// authenticated api routes
Route::middleware(['auth:api'])->as('api.')->group(function () {
    Route::post('auth/logout', 'Api\AuthController@logout')->name('api.auth.logout');

    // additional api/user/ routes
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('profile', 'Api\AuthController@profile')->name('profile');
        Route::get('permissions', 'Api\AuthController@getPermissions')->name('permissions');
        // Route::post('logout', 'Api\AuthController@logout')->name('logout');
    });

    // api/
    Route::apiResources([
        'role'       => 'Api\RolesController',
        'user'       => 'Api\UsersController',
        'permission' => 'Api\PermissionsController',
    ]);

    // audit routes
    Route::get('audit', 'Api\AuditsController@index')->name('audit.index');
});
