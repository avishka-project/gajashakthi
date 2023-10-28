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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/AuthenticateUser',['uses' => '\App\Http\Controllers\api\AuthController@AuthenticateUser', 'as' => 'AuthenticateUser']);

Route::middleware('auth:api')->group(function () {
    Route::post('v1/GetCustomerBranches', ['uses' => '\App\Http\Controllers\api\MainController@getCustomerBranches', 'as' => 'GetCustomerBranches']);
    Route::post('v1/AttendanceStore', ['uses' => '\App\Http\Controllers\api\MainController@attendanceStore', 'as' => 'AttendanceStore']);
    Route::post('v1/GetEmployeeInfo', ['uses' => '\App\Http\Controllers\api\MainController@getEmployeeInfo', 'as' => 'GetEmployeeInfo']);
    Route::post('v1/EmpLocationStore', ['uses' => '\App\Http\Controllers\api\MainController@empLocationStore', 'as' => 'EmpLocationStore']);
});
