<?php
namespace App\Helper;

use Slim\Http\Request;

class Auth
{

    private $app;

    /**
     *
     * @param unknown $app            
     */
    function __construct($app)
    {
        $this->app = $app;
    }

    function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
    }

    /**
     *
     * @param Request $request            
     * @param unknown $response            
     * @param unknown $next            
     * @return unknown
     */
    public function __invoke(Request $request, $response, $next)
    {
        $container = $this->app->getContainer();
        $session = $container->get('session');
        
        // if not an admin section
        if ($this->startsWith($request->getUri()
            ->getPath(), '/admin/login')) {
            $response = $next($request, $response);
            return $response;
        }
        
        if (! $this->isLoggedIn($session)) {
            
            $session->set('original', $request->getUri()
                ->getPath());
            
            $view = $container->get('view');
            $router = $container->get('router');
            
            return $response->withRedirect($router->pathFor('adminlogin'));
            
            //return $view->render($response, 'adminlogin.twig', array());
        } else {
            $response = $next($request, $response);
        }
        
        return $response;
    }

    private function isLoggedIn(Session $session)
    {
        return ($session->get('authenticated'));
    }
}