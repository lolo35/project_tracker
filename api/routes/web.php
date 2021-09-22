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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group(['prefix' => 'teams'], function() use ($router){
    $router->post('/add', ['uses' => 'TeamsController@addTeam']);
    $router->get('/', ['uses' => 'TeamsController@getTeams']);
    $router->post('/delete', ['uses' => "TeamsController@deleteTeam"]);
});

$router->group(['prefix' => 'user'], function() use ($router){
    $router->get('/users', ['uses' => 'UsersController@getUsers']);
    $router->post('/login', ['uses' => 'UsersController@validateLogin']);
});