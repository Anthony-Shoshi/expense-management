<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

//routes for the authentication
$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');

// routes for the expenses endpoint
$router->get('/expenses', 'ExpenseController@getAllExpenses');
$router->get('/expenses/(\d+)', 'ExpenseController@getExpense');
$router->post('/expenses', 'ExpenseController@createExpense');
$router->put('/expenses/(\d+)', 'ExpenseController@updateExpense');
$router->delete('/expenses/(\d+)', 'ExpenseController@deleteExpense');
$router->get('/expenses/category/(\d+)', 'ExpenseController@getAllByCategoryId');
$router->get('/expenses/user/(\d+)', 'ExpenseController@getAllByUserId');
$router->get('/expenses/search', 'ExpenseController@searchExpenses');

// routes for the categories endpoint
$router->get('/categories', 'CategoryController@getAll');
$router->get('/categories/(\d+)', 'CategoryController@getOne');
$router->post('/categories', 'CategoryController@create');
$router->put('/categories/(\d+)', 'CategoryController@update');
$router->delete('/categories/(\d+)', 'CategoryController@delete');

$router->get('/users', 'UserController@getAll');

// Run it!
$router->run();