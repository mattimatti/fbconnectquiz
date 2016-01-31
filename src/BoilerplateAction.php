<?php
namespace App;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;

final class BoilerplateAction
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

    public function __construct(\Slim\Container $container)
    {
        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->settings = $container->get('settings');
    }

    public function foo(Request $request, Response $response, $args)
    {}
}