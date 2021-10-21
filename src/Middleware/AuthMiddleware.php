<?php

namespace App\Middleware;

use Slim\Router;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (!isset($_SESSION['user'])) {
            return $response->withRedirect($this->router->pathFor('security_login'));
        }

        return $next($request, $response);
    }
}
