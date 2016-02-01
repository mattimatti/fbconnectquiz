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

/**
 *
 * @author mattimatti
 *        
 */
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
        
        $this->viewData = array();
        $this->viewData['permissions'] = json_encode($this->settings["facebook-permissions"]);
        $this->viewData['theme'] = 'starwars';
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function submit(Request $request, Response $response, $args)
    {
        $selected = $_POST['selection'];
        
        $this->viewData['quiz'] = $this->quiz->getOptions();
        
        if (isset($_POST['selection'])) {
            $selectedAnswer = $this->quiz->getAnswer(1, $selected);
            if ($selectedAnswer) {
                $this->viewData['answer'] = $selectedAnswer;
                $this->viewData['selecteditem'] = true;
                return $this->view->render($response, 'index.twig', $this->viewData);
            }
        }
        
        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     */
    private function handleLogin()
    {
        // if (! $this->facebook->hasAccessToken()) {
        // return $response->withRedirect($this->router->pathFor('login'));
        // }
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function index(Request $request, Response $response, $args)
    {
        $this->handleLogin();
        
        if ($this->facebook->hasAccessToken()) {
            try {
                
                $this->logger->debug('facebook load data');
                
                $profile = $this->facebook->retriveProfile();
                
                $friends = $this->facebook->retriveFriends();
            } catch (\Exception $ex) {
                
                $this->logger->error($ex->getMessage());
                
                return $response->withRedirect($this->router->pathFor('login'));
            }
        }
        
        $this->viewData['quiz'] = $this->quiz->getOptions();
        
        return $this->view->render($response, 'index.twig', $this->viewData);
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function install(Request $request, Response $response, $args)
    {
        $data = $this->quiz->populate();
        return $response->withRedirect($this->router->pathFor('login'));
    }
}