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

Route::group([
    'middleware' => ['cors'] // 防跨域
], function ($router) {

    // 前端接口
    $router->group([
        'namespace' => 'Api' // 命名空间
    ], function ($router) {
        // 用户登录
        $router->post('login', 'UserAuthController@login');

        $router->group(['middleware' => ['auth:api,user']], function ($router) {
            // 退出登陆
            $router->delete('logout', 'UserAuthController@logout');
        });
    });

});
