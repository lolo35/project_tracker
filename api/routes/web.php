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
    $router->post('/editTaskDescription', ['uses' => "TasksController@editTaskDescription"]);
    $router->post('/createUser', ['uses' => "UsersController@createUser"]);
    $router->post('/changePass', ['uses' => "UsersController@changePassword"]);
});

$router->group(['prefix' => 'projects'], function() use($router) {
    $router->get('/', ['uses' => "ProjectsController@getProjects"]);
    $router->post('/addProject', ['uses' => "ProjectsController@addProject"]);
    $router->get('/projectStats', ['uses' => "ProjectsController@percentDone"]);
    $router->get('/projectDetails', ['uses' => "ProjectsController@getTasksForProject"]);
    $router->post('/changePriority', ['uses' => "ProjectsController@changePriority"]);
    $router->get('/priorityHistory', ['uses' => "ProjectsController@getProjectHistory"]);
    $router->get('/projectById', ['uses' => "ProjectsController@getProjectById"]);
});

$router->group(['prefix' => 'recurring'], function () use($router){
    $router->get('/', ['uses' => "RecurringTasksController@getTasks"]);
    $router->get('/dailyTasks', ['uses' => "RecurringTasksController@getDailyTasks"]);
    $router->post('/addTask', ['uses' => 'RecurringTasksController@addTask']);
    $router->post('/deleteTask', ['uses' => 'RecurringTasksController@deleteTask']);
    $router->post('/activateTask', ['uses' => 'RecurringTasksController@activateTask']);
    $router->post('/completeTask', ['uses' => 'RecurringTasksController@completeTask']);
    $router->get('/recurringHistory', ['uses' => "RecurringTasksHistoryController@recurringHistoryForUser"]);
    $router->post('/changeTimeframe', ['uses' => "RecurringTasksController@changeTimeFrame"]);
});

$router->group(['prefix' => 'forum'], function () use ($router){
    $router->get('/', ['uses' => 'ForumCategoryController@getCategories']);
    $router->get('/topics', ['uses' => 'ForumTopicController@getTopicsForCategory']);
    $router->get('/topic', ['uses' => 'ForumTopicController@getTopic']);
    $router->get('/topicPosts', ['uses' => 'ForumPostsController@getPostsForTopic']);
    $router->post('/addTopic', ['uses' => 'ForumPostsController@createTopic']);
    $router->get('/topicDiscution', ['uses' => "ForumPostsController@getTopicDiscution"]);
    $router->post('/postReply', ['uses' => "ForumPostsController@postReply"]);
    $router->post('/addView', ['uses' => 'ForumPostsController@addView']);
});

$router->group(['prefix' => 'issues'], function () use ($router){
    $router->get('/countIssues', ['uses' => "IssuesController@countIssues"]);
    $router->post('/addIssue', ['uses' => "IssuesController@addIssue"]);
    $router->get('/issuesForProject', ['uses' => "IssuesController@fetchIssues"]);
    $router->post('/closeIssue', ['uses' => "IssuesController@closeIssue"]);
    $router->get('/closedBy', ['uses' => "IssuesController@fetchClosedBy"]);
});

$router->group(['prefix' => 'charts'], function () use ($router) {
    $router->get('/tasksSummary', ['uses' => "ChartsController@countTasks"]);
    $router->get('/taskCountEvolution', ['uses' => "ChartsController@taskCountEvolution"]);
    $router->get('/latestIssues', ['uses' => "ChartsController@latestIssues"]);
    $router->get('/issueCount', ['uses' => "ChartsController@countIssues"]);
    $router->get('/latestProjects', ['uses' => "ChartsController@latestProjects"]);
});