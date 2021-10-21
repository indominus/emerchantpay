<?php

namespace App\Controller\Admin;

use Throwable;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends BaseController
{

    /**
     * @throws Throwable
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        return $response->withRedirect($this->getRouter()->pathFor('posts_list'));
    }
}
