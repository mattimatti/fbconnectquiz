<?php
namespace App\Facebook;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Monolog\Logger;
use RedBeanPHP;

final class LoginAction
{

    private $view;

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

    /**
     *
     * @var Facebook
     */
    private $facebook;

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->router = $container->get('router');
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
        
        $accessToken = null;
        
        try {
            
            $accessToken = $helper->getAccessToken();
            $this->logger->debug('Got new access token! : ' . $accessToken);
            
        } catch (FacebookResponseException $e) {
            $this->logger->error('error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->logger->error('Facebook SDK returned an error: ' . $e->getMessage());
        }
        
            
            $this->logger->debug('Store access token : ' . $accessToken);
            
            $_SESSION['facebook_access_token'] = $accessToken;
            
            $this->logger->debug('Stored access token is : ' . $_SESSION['facebook_access_token']);
        
        return $response->withRedirect($this->router->pathFor('home'));
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
        
        $loginUrl = $helper->getLoginUrl($baseDomain . '/login-callback', $permissions);
        
        $view['loginUrl'] = $loginUrl;
        
        $this->view->render($response, 'login.twig', $view);
        return $response;
    }
}