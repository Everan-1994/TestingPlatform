<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 员工管理
    $router->get('/staff', 'UserController@index');
    $router->get('/staff/create', 'UserController@create');
    $router->post('/staff', 'UserController@store');
    $router->get('/staff/{id}/edit', 'UserController@edit');
    $router->put('/staff/{id}', 'UserController@update');
    $router->delete('/staff/{id}', 'UserController@destroy');
});
