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
final class AdminController
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
    public function login(Request $request, Response $response, $args)
    {
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
            
            if ($password == 'lyrics2016') {
                $this->session->set('authenticated', 'yes');
                return $response->withRedirect($this->session->get('original'));
            }
        }
        return $this->view->render($response, 'adminlogin.twig', array());
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function logout(Request $request, Response $response, $args)
    {
        $this->session->delete('authenticated');
        return $response->withRedirect($this->router->pathFor('results'));
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function resultsdelete(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        
        $record = R::load(USER, $id);
        
        if ($record) {
            R::trash($record);
        }
        
        return $response->withRedirect($this->router->pathFor('results'));
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function results(Request $request, Response $response, $args)
    {
        $this->viewData['results'] = $this->quiz->getResults();
        $this->viewData['cumulative'] = $this->quiz->getCumulativeResults();
        return $this->view->render($response, 'results.twig', $this->viewData);
    }
}