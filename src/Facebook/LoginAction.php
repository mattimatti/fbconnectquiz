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
    public function callback(Request $request, Response $response, $args)
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        
        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            
            $this->view->render($response, 'login-error.twig');
            return $response;
            
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit();
        } catch (FacebookSDKException $e) {
            
            $this->view->render($response, 'login-error.twig');
            return $response;
            
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit();
        }
        
        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            
            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        }
    }

    /**
     * Init the login action
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     * @return Response
     */
    public function intent(Request $request, Response $response, $args)
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        
        $permissions = $this->settings['facebook']['permissions'];
        $baseDomain = $this->settings['baseDomain'];
        
        $loginUrl = $helper->getLoginUrl($baseDomain .'/login-callback', $permissions);
        
        $view['loginUrl'] = $loginUrl;
        
        $this->view->render($response, 'login.twig', $view);
        return $response;
    }
}