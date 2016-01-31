<?php
use App\Controller\Quiz;
// Routes

$app->get('/login-callback', 'App\Controller\FacebookConnect:callback')->setName('login-callback');
$app->get('/login', 'App\Controller\FacebookConnect:login')->setName('login');

$app->get('/', 'App\Controller\Quiz:index')->setName('home');
$app->post('/', 'App\Controller\Quiz:submit');