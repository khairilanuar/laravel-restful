<?php

use Illuminate\Http\Request;

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

Route::post('user/register', 'Api\UserController@register')->name('api.user.register');

Route::middleware(['auth:api'])->as('api.')->group(function () {
    // additional api/user/ routes
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('profile', 'Api\UserController@profile')->name('profile');
        Route::get('permissions', 'Api\UserController@getPermissions')->name('permissions');
        Route::post('logout', 'Api\UserController@logout')->name('logout');
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
