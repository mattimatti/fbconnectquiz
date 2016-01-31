<?php
namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Slim\Router;
use App\Helper\Session;
use App\Quiz\QuizService;

final class Quiz
{

    private $view;

    /**
     *
     * @var Router
     */
    private $router;

    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var QuizService
     */
    private $quiz;

    /**
     *
     * @var Session
     */
    private $session;

    /**
     *
     * @var array
     */
    private $settings;

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->router = $container->get('router');
        $this->logger = $container->get('logger');
        $this->settings = $container->get('settings');
        $this->session = $container->get('session');
        $this->quiz = $container->get('quiz');
    }

    public function submit(Request $request, Response $response, $args)
    {
//         $this->logger->debug(print_r($request, true));
        print_r($_POST);
        exit();
    }

    public function index(Request $request, Response $response, $args)
    {
        $this->logger->debug('entrain home');
        $this->logger->debug('session : ' . print_r($_SESSION, 1));
        
        if (! $this->session->get('facebook_access_token')) {
            return $response->withRedirect($this->router->pathFor('login'));
        }
        
        $data = $this->quiz->getOptions();
        
        // print_r($data);
        // exit();
        
        return $this->view->render($response, 'index.twig', $data);
    }
}