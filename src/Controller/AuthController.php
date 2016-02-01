<?php
namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Monolog\Logger;
use RedBeanPHP;
use App\Helper\Session;
use App\Facebook\Connect;

final class AuthController
{

    private $view;

    /**
     *
     * @var Logger
     */
    private $logger;

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

    /**
     *
     * @var Connect
     */
    private $facebook;

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->router = $container->get('router');
        $this->logger = $container->get('logger');
        $this->facebook = $container->get('facebook');
        $this->settings = $container->get('settings');
        $this->session = $container->get('session');
        
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
    public function callback(Request $request, Response $response, $args)
    {
        $this->session->clear();
        
        
        if ($this->facebook->getAccessToken()) {
            return $response->withRedirect($this->router->pathFor('home'));
        }
        
        return $response->withRedirect($this->router->pathFor('login'));
    }

    /**
     * Init the login action
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     * @return Response
     */
    public function login(Request $request, Response $response, $args)
    {
//         $helper = $this->facebook->getRedirectLoginHelper();
        
//         $permissions = $this->settings['facebook-permissions'];
//         $baseDomain = $this->settings['baseDomain'];
        
//         $loginUrl = $helper->getLoginUrl($baseDomain . '/login-callback', $permissions);
        
//         $view['loginUrl'] = $loginUrl;

        $this->session->clear();
        
        
        $this->view->render($response, 'login.twig',  $this->viewData);
        return $response;
    }
}