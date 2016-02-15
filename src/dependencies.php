<?php
use App\Helper\Session;
use App\Quiz\Service;
use App\Quiz\QuizService;
use RedBeanPHP\R;
use RedBeanPHP\OODB;
use App\Facebook\Connect;
// DIC configuration
$container = $app->getContainer();



// // Error Handler
// $container['errorHandler'] = function ($c) {
//     return function ($request, $response, $exception) use ($c) {
//         return $c['response']->withStatus(500)
//         ->withHeader('Content-Type', 'text/html')
//         ->write('Something went wrong!');
//     };
// };




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
    
    $connect = new Connect($settings, $session, $logger);
    
    return $connect;
};

$container['database'] = function ($c)
{
    $logger = $c->get('logger');
    $settings = $c->get('settings')['database'];
    
    define('QUESTION', 'quiz_question');
    define('ANSWER', 'quiz_answer');
    define('QUIZ', 'quiz_quiz');
    define('USER', 'quiz_user');
    
    
    
    
    // Create an extension to by-pass security check in R::dispense
    R::ext('xdispense', function ($type)
    {
        return R::getRedBean()->dispense($type);
    });
    
    R::renameAssociation([
        'quiz_answer_quiz_question' => 'quiz_answer_question',
        'quiz_answer_quiz_quiz' => 'quiz_answer_quiz',
        'quiz_question_quiz_quiz' => 'quiz_question_quiz'
    ]);
    
    define('REDBEAN_MODEL_PREFIX', '\\App\\Model\\');
    
    $connectionString = 'mysql:host=' . $settings['host'] . ';dbname=' . $settings['dbname'];
    
    R::setup($connectionString, $settings['username'], $settings['password']);
    
    R::useWriterCache(true);
    
    //R::debug(true);
    
    // // NEVER REMOVE THIS!
    R::freeze(true);
    
    return R::getRedBean();
};

$container['quiz'] = function ($c)
{
    $database = $c->get('database');
    return new QuizService();
};








