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
use App\Facebook\Connect;

final class QuizController
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
     * @var Connect
     */
    private $facebook;

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
        $this->facebook = $container->get('facebook');
        $this->quiz = $container->get('quiz');
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function submit(Request $request, Response $response, $args)
    {
       // $selected = $_POST['selected'];
        
        $data = array();
        $data['quiz'] = $this->quiz->getOptions();
        $data['answer'] = $this->quiz->getAnswer(1, 1);
        $data['selecteditem'] = true;
        
        return $this->view->render($response, 'index.twig', $data);
        
        // $this->quiz
        // $this->logger->debug(print_r($request, true));
        print_r($_POST);
        exit();
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function index(Request $request, Response $response, $args)
    {
        if (! $this->session->get('facebook_access_token')) {
            return $response->withRedirect($this->router->pathFor('login'));
        }
        
        $profile = $this->facebook->retriveProfile();
        $data = $this->quiz->getOptions();
        
        return $this->view->render($response, 'index.twig', $data);
    }
}