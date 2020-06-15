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
            // 更新信息
            $router->put('update/info', 'UserAuthController@updateInfo');
            // 取样
            $router->get('sample', 'SampleController@index');
            // 取样下一步
            $router->post('sample/next', 'SampleController@nextStep');
            // 提交报告
            $router->post('report', 'SampleController@report');
            // 项目
            $router->get('project', 'ProjectController@index');
            // 设备
            $router->get('device', 'DeviceController@index');
            // 退出登陆
            $router->delete('logout', 'UserAuthController@logout');
        });
    });

});
