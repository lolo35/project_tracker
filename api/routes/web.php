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
    $router->get("/team", ["uses" => "TeamsController@getMyTeam"]);
    $router->post('/addTeamMember', ['uses' => "TeamsController@addTeamMember"]);
    $router->post("/removeTeamMember", ['uses' => "TeamsController@deleteTeamMember"]);
    $router->get('/workingTasks', ['uses' => "TasksController@currentlyWorkingTasks"]);
});

$router->group(['prefix' => 'user'], function() use ($router){
    $router->get('/users', ['uses' => 'UsersController@getUsers']);
    $router->post('/login', ['uses' => 'UsersController@validateLogin']);
    $router->get('/tasks', ['uses' => "TasksController@getTasks"]);
    $router->post('/addTask', ['uses' => "TasksController@addTask"]);
    $router->post('/updateTask', ['uses' => "TasksController@updateTask"]);
    $router->post('/deleteTask', ['uses' => "TasksController@deleteTask"]);
    $router->post('/toggleTask', ['uses' => "TasksController@toggleTask"]);
});

$router->group(['prefix' => 'projects'], function() use($router) {
    $router->get('/', ['uses' => "ProjectsController@getProjects"]);
    $router->post('/addProject', ['uses' => "ProjectsController@addProject"]);
    $router->get('/projectStats', ['uses' => "ProjectsController@percentDone"]);
    $router->get('/projectDetails', ['uses' => "ProjectsController@getTasksForProject"]);
});

$router->group(['prefix' => 'recurring'], function () use($router){
    $router->get('/', ['uses' => "RecurringTasksController@getTasks"]);
    $router->get('/dailyTasks', ['uses' => "RecurringTasksController@getDailyTasks"]);
    $router->post('/addTask', ['uses' => 'RecurringTasksController@addTask']);
    $router->post('/deleteTask', ['uses' => 'RecurringTasksController@deleteTask']);
    $router->post('/activateTask', ['uses' => 'RecurringTasksController@activateTask']);
    $router->post('/completeTask', ['uses' => 'RecurringTasksController@completeTask']);
});