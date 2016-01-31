<?php
// Routes
$app->get('/login-callback', 'App\Facebook\LoginAction:callback')->setName('login-callback');

$app->get('/login', 'App\Facebook\LoginAction:intent')->setName('login');

$app->get('/[{name}]', 'App\Quiz\EngineAction:index')->setName('home');


