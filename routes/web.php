<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/user/register', 'UserController@register');
$router->post('/user/login', 'UserController@login');

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/user/profile', function () {
        return response()->json(auth()->user());
    });

    $router->post('/todo/add', 'TodoController@create');
    $router->get('/todo/list', 'TodoController@list');
    $router->get('/todo/{id}', 'TodoController@getOne');
    $router->put('/todo/update/{id}', 'TodoController@update');
    $router->delete('/todo/delete/{id}', 'TodoController@delete');
});
