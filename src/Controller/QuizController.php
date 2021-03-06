<?php
namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Slim\Router;
use RedBeanPHP\R;
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
            
            if (! $this->captateUser($response)) {
                return $response->withRedirect($this->router->pathFor('login'));
            }
            
            $this->logger->debug('evaluate user selection : ' . $selected);
            
            $selectedAnswer = $this->quiz->getAnswer(1, $selected);
            
            if ($selectedAnswer) {
                
                $this->logger->debug('Found answer, display result');
                
                $this->viewData['answer'] = $selectedAnswer;
                $this->viewData['selecteditem'] = true;
                $this->viewData['shareurl'] = $this->settings['baseDomain'] . '/shared/' . $selectedAnswer->getId();
                
                return $this->view->render($response, 'index.twig', $this->viewData);
            }
        }
        
        return $response->withRedirect($this->router->pathFor('home'));
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
        if (! $this->captateUser()) {
            return $response->withRedirect($this->router->pathFor('login'));
        }
        
        $this->logger->debug('render quiz page');
        
        $this->viewData['quiz'] = $this->quiz->getOptions(1);
        
        return $this->view->render($response, 'index.twig', $this->viewData);
    }

    /**
     * 
     * @param unknown $response
     * @return boolean
     */
    public function captateUser()
    {
        
        
        if ($this->facebook->hasAccessToken()) {
            
            $this->logger->debug('user has access token : ' . $this->facebook->getAccessToken());
            
            try {
                
                $this->logger->debug('facebook load data');
                
                // load and store profile.
                $profile = $this->facebook->retriveProfile();
                
                // geolocate and store the ip
                $location = $this->facebook->retriveLocationFromIp();
                if ($location) {
                    $this->facebook->storeLocationInProfile($location);
                } else {
                    $this->logger->error('Unable to geolocate user');
                }
                
                return true;
            } catch (\Exception $ex) {
                
                $this->logger->error($_SERVER['HTTP_USER_AGENT'] . ' - ' . $ex->getMessage());
                
                return false;
            }
        }
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
        $this->viewData['shareurl'] = $this->settings['baseDomain'] . '/shared/' . $selectedAnswer->getId();
        
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

    
    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function alter(Request $request, Response $response, $args)
    {
        $data = $this->quiz->alter();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function privacy(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'privacy.twig', $this->viewData);
    }

    public function results(Request $request, Response $response, $args)
    {
        return $response->withRedirect($this->router->pathFor('results'));
    }
}