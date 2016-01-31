<?php
use App\Helper\Session;
// DIC configuration
$container = $app->getContainer();

// Twig
$container['view'] = function ($c)
{
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')
        ->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};

// Flash messages
$container['flash'] = function ($c)
{
    return new \Slim\Flash\Messages();
};

// monolog
$container['logger'] = function ($c)
{
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// facebook service
$container['facebook'] = function ($c)
{
    
    $settings = $c->get('settings')['facebook'];
    $facebook = new Facebook\Facebook($settings);
    
    // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
    // $helper = $fb->getRedirectLoginHelper();
    // $helper = $fb->getJavaScriptHelper();
    // $helper = $fb->getCanvasHelper();
    // $helper = $fb->getPageTabHelper();
    
    return $facebook;
};


$container['session'] = function($c){
    return new Session();
};








