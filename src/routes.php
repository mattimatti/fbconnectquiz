<?php
use App\Controller\QuizController;
// Routes

$app->get('/login-callback', 'App\Controller\AuthController:callback')->setName('login-callback');
$app->get('/login', 'App\Controller\AuthController:login')->setName('login');

$app->get('/privacy', 'App\Controller\QuizController:privacy')->setName('privacy');
$app->get('/install', 'App\Controller\QuizController:install')->setName('install');
$app->get('/alter', 'App\Controller\QuizController:alter')->setName('alter');

$app->get('/xyz', 'App\Controller\QuizController:results');

$app->group('/admin', function () use($app)
{
    
    $app->get('/xyz', 'App\Controller\AdminController:results')
        ->setName('results');
    $app->get('/results/delete/{id}', 'App\Controller\AdminController:resultsdelete');
    $app->map(array('GET','POST'),'/login', 'App\Controller\AdminController:login')->setName('adminlogin');
    $app->get('/logout', 'App\Controller\AdminController:logout');
})
    ->add(new \App\Helper\Auth($app));

// shared
$app->get('/shared/{id}', 'App\Controller\QuizController:share')->setName('share');
$app->get('/', 'App\Controller\QuizController:index')->setName('home');
$app->post('/', 'App\Controller\QuizController:submit');