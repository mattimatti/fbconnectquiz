<?php
use App\Helper\Session;
use App\Quiz\Service;
use App\Quiz\QuizService;
use RedBeanPHP\R;
use RedBeanPHP\OODB;
use App\Facebook\Connect;
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

// // Flash messages
// $container['flash'] = function ($c)
// {
// return new \Slim\Flash\Messages();
// };

// monolog
$container['logger'] = function ($c)
{
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['session'] = function ($c)
{
    return new Session();
};

// facebook service
$container['facebook'] = function ($c)
{
    $logger = $c->get('logger');
    $session = $c->get('session');
    
    $settings = $c->get('settings')['facebook'];
    
    $connect = new Connect(settings, $session, $logger);
    
    return $connect;
};

$container['quiz'] = function ($c)
{
    return new QuizService();
};

$container['db'] = function ($c)
{
    
    $settings = $c->get('settings')['db'];
    
    OODB::autoClearHistoryAfterStore(TRUE);
    define('REDBEAN_MODEL_PREFIX', '\\App\\Model\\');
    
    R::setup('mysql:host=' . $settings['host'] . ';dbname=' . $settings['dbname'], $settings['username'], $settings['password']);
    
    // NEVER REMOVE THIS!
    R::freeze(true);
};









