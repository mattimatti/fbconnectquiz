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
        $this->viewData['settings'] = $this->settings;
        $this->viewData['theme'] = 'starwars';
        $this->viewData['version'] = 1;
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
        
        $this->viewData['quiz'] = $this->quiz->getOptions(1);
        
        if (isset($_POST['selection'])) {
            
           $this->logger->debug('evaluate user selection : ' . $selected);
            
            $selectedAnswer = $this->quiz->getAnswer(1, $selected);
            if ($selectedAnswer) {
                
                $this->logger->debug('Found answer, display result');
                
                $this->viewData['answer'] = $selectedAnswer;
                $this->viewData['selecteditem'] = true;
                $this->viewData['shareurl'] = $this->settings['baseDomain'] .'/shared/'.$selectedAnswer->getId();
                
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

    
    private function handleRedirect(Response $response)
    {
        if (! $this->facebook->hasAccessToken()) {
            return $response->withRedirect($this->router->pathFor('home'));
        }
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
            
            $this->logger->debug('user has access token : ' . $this->facebook->getAccessToken());
            
            try {
                
                $this->logger->debug('facebook load data');
                
                $profile = $this->facebook->retriveProfile();
                
                $friends = $this->facebook->retriveFriends();
                
                $allfriends = $this->facebook->retriveAllFriends();
                
            } catch (\Exception $ex) {
                
                $this->logger->error($ex->getMessage());
                
                return $response->withRedirect($this->router->pathFor('login'));
            }
        }

        $this->logger->debug('render quiz page');
        
        $this->viewData['quiz'] = $this->quiz->getOptions(1);
        
        return $this->view->render($response, 'index.twig', $this->viewData);
    }

    
    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function share(Request $request, Response $response, $args)
    {
        
        
        $pos = strrpos($_SERVER['HTTP_USER_AGENT'], "facebook");
        if ($pos === false) { // note: three equal signs
            return $response->withRedirect($this->router->pathFor('home'));            
        }
        
        $this->logger->debug(print_r($_SERVER, true));
        //
        
        $selectedAnswer = $this->quiz->getAnswer(1, $args['id']);
        $this->viewData['quiz'] = $this->quiz->getOptions(1);
        $this->viewData['answer'] = $selectedAnswer;
        $this->viewData['selecteditem'] = true;
        $this->viewData['shareurl'] = $this->settings['baseDomain'] .'/shared/'.$selectedAnswer->getId();
        
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