<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('auth/login', 'AuthController@login');

$router->post('deliverycustomer/newregister', 'DeliveryCustomerController@register');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user', function () {
        return response()->json(auth()->user());
    });
    $router->post('user/newpassword', 'UserController@changePassword');
    $router->post('user/newregister', 'UserController@register');

    $router->get('users', 'UserController@list');
    $router->get('user/{id}', 'UserController@getById');
    $router->get('user/{id}/set/status/{status}', 'UserController@setStatus');

    $router->get('deliverycustomer/{id}/set/status/{status}', 'DeliveryCustomerController@setStatus');
    $router->get('deliverycustomers', 'DeliveryCustomerController@list');
    $router->get('deliverycustomer/{id}', 'DeliveryCustomerController@getById');
    $router->post('deliverycustomer/from/phone', 'DeliveryCustomerController@getByPhone');

    $router->get('notification/status/{status}', 'NotifCustomerController@list');
    $router->get('notification/setasviewed/{notificationID}', 'NotifCustomerController@setAsViewed');
    $router->get('notification/clearall', 'NotifCustomerController@clearAll');
});

$router->get('/', function () use ($router) {
    return response()->json('ok');
});

/*$router->get('/', function () use ($router) {
    return $router->app->version();
});*/