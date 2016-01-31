<?php
// Routes
$app->get('/login-callback', 'App\Facebook\ConnectAction:callback')->setName('login-callback');

$app->get('/login', 'App\Facebook\ConnectAction:login')->setName('login');

$app->get('/', 'App\Quiz\EngineAction:index')->setName('home');
