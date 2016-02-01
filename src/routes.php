<?php
use App\Controller\QuizController;
// Routes

$app->get('/login-callback', 'App\Controller\AuthController:callback')->setName('login-callback');
$app->get('/login', 'App\Controller\AuthController:login')->setName('login');



$app->get('/install', 'App\Controller\QuizController:install')->setName('install');

$app->get('/', 'App\Controller\QuizController:index')->setName('home');
$app->post('/', 'App\Controller\QuizController:submit');