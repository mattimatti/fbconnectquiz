<?php
namespace App\Quiz;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Slim\Router;

final class EngineAction
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
     * @var array
     */
    private $settings;

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->router = $container->get('router');
        $this->logger = $container->get('logger');
        $this->settings = $container->get('settings');
    }

    public function index(Request $request, Response $response, $args)
    {
        $this->logger->debug('ACCESS TOKEN IS : ' . $_SESSION['facebook_access_token']);
        
        if (! isset($_SESSION['facebook_access_token'])) {
            exit('merda');
//            return $response->withRedirect($this->router->pathFor('login'));
        }
        
        return $this->view->render($response, 'index.twig', $args);
    }
}