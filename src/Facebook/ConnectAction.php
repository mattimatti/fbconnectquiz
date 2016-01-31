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
use App\Helper\Session;

final class ConnectAction
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
        $this->session = $container->get('session');
    }

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function callback(Request $request, Response $response, $args)
    {
        $helper = $this->facebook->getJavaScriptHelper();
        
        try {
            
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            $this->logger->error('error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->logger->error('Facebook SDK returned an error: ' . $e->getMessage());
        }
        if (! isset($accessToken)) {
            $this->logger->error('No cookie set or no OAuth data could be obtained from cookie.');
        }else {
            $this->logger->debug('Got new access token! : ' . $accessToken);
            $this->logger->debug('Store access token : ' . $accessToken);
            
            $this->session->set('facebook_access_token', (string) $accessToken);
            
            $this->logger->debug('Stored access token is : ' . $_SESSION['facebook_access_token']);
            
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
        $helper = $this->facebook->getRedirectLoginHelper();
        
        $permissions = $this->settings['facebook-permissions'];
        $baseDomain = $this->settings['baseDomain'];
        
        $loginUrl = $helper->getLoginUrl($baseDomain . '/login-callback', $permissions);
        
        $view['loginUrl'] = $loginUrl;
        
        $this->view->render($response, 'login.twig', $view);
        return $response;
    }
}