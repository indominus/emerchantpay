<?php

namespace App\Controller;

use PDO;
use Throwable;
use Slim\Router;
use Slim\Views\Twig;
use App\Services\Database;
use Slim\Views\PhpRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class BaseController
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return Database
     */
    public function getDB(): Database
    {
        return $this->container->get('db');
    }

    /**
     * @return Twig
     */
    public function getView(): Twig
    {
        return $this->container->get('view');
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->container->get('router');
    }

    /**
     * @throws Throwable
     */
    public function render($response, $template, array $data = []): ResponseInterface
    {
        return $this->getView()->render($response, $template, $data);
    }
}
