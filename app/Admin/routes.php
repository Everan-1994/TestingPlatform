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

    // 样品管理
    $router->get('/sample', 'SampleController@index');
    $router->get('/sample/create', 'SampleController@create');
    $router->post('/sample', 'SampleController@store');
    $router->get('/sample/{id}/edit', 'SampleController@edit');
    $router->put('/sample/{id}', 'SampleController@update');
    $router->delete('/sample/{id}', 'SampleController@destroy');

    // 设备
    $router->get('/device', 'DeviceController@index');
    $router->get('/device/create', 'DeviceController@create');
    $router->post('/device', 'DeviceController@store');
    $router->get('/device/{id}/edit', 'DeviceController@edit');
    $router->put('/device/{id}', 'DeviceController@update');
    $router->delete('/device/{id}', 'DeviceController@destroy');

    // 项目
    $router->get('/project', 'ProjectController@index');
    $router->get('/project/create', 'ProjectController@create');
    $router->post('/project', 'ProjectController@store');
    $router->get('/project/{id}/edit', 'ProjectController@edit');
    $router->put('/project/{id}', 'ProjectController@update');
    $router->delete('/project/{id}', 'ProjectController@destroy');

    // 物资
    $router->get('/supply', 'SupplyController@index');
    $router->get('/supply/create', 'SupplyController@create');
    $router->post('/supply', 'SupplyController@store');
    $router->get('/supply/{id}/edit', 'SupplyController@edit');
    $router->put('/supply/{id}', 'SupplyController@update');
    $router->delete('/supply/{id}', 'SupplyController@destroy');

    // 到货记录
    $router->get('/supplies_arrival', 'SuppliesArrivalController@index')->name('admin.supply.arrival');
    $router->get('/supplies_arrival/create', 'SuppliesArrivalController@create')->name('admin.supply.arrival.create');
    $router->post('/supplies_arrival', 'SuppliesArrivalController@store');
    // 领用记录
    $router->get('/supplies_receive', 'SuppliesReceiveController@index')->name('admin.supply.receive');
    $router->get('/supplies_receive/create', 'SuppliesReceiveController@create')->name('admin.supply.receive.create');
    $router->post('/supplies_receive', 'SuppliesReceiveController@store');
});
