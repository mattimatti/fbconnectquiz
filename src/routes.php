<?php
// Routes


$app->get('/', 'App\Facebook\LoginAction:intent')
    ->setName('login');

$app->get('/login-callback', 'App\Facebook\LoginAction:callback')
    ->setName('login-callback');



/*
$app->get('/[{name}]', function ($request, $response, $args) {
    

	


    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/
