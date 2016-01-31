<?php
namespace App\Facebook;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

final class LoginAction
{

    private $view;

    private $logger;

    /**
     *
     * @var array
     */
    private $settings;

    /**
     *
     * @var Facebook
     */
    private $facebook;

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->facebook = $container->get('facebook');
        $this->settings = $container->get('settings');
    }

    
    /**
     * 
     * @param Request $request
     * @param Response $response
     * @param unknown $args
     */
    public function loginCallback(Request $request, Response $response, $args)
    {}

    
    
    /**
     * 
     * @param Request $request
     * @param Response $response
     * @param unknown $args
     * @return Response
     */
    public function login(Request $request, Response $response, $args)
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        
        $permissions = [
            'email',
            'user_likes'
        ]; // optional
        $loginUrl = $helper->getLoginUrl('http://{your-website}/login-callback', $permissions);
        
        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
        
        // try {
        // // Get the Facebook\GraphNodes\GraphUser object for the current user.
        // // If you provided a 'default_access_token', the '{access-token}' is optional.
        
        // $response = $this->facebook->get('/me');
        
        // } catch (FacebookResponseException $e) {
        // // When Graph returns an error
        // echo 'Graph returned an error: ' . $e->getMessage();
        // exit();
        // } catch (FacebookSDKException $e) {
        // // When validation fails or other local issues
        // echo 'Facebook SDK returned an error: ' . $e->getMessage();
        // exit();
        // }
        
        // $me = $response->getGraphUser();
        // echo 'Logged in as ' . $me->getName();
        
        $this->logger->info("Home page action dispatched");
        
        //$this->view->render($response, 'index.twig');
        return $response;
    }
}