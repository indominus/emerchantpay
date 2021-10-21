<?php

namespace App\Controller;

use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomepageController extends BaseController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        return $this->render($response, 'layout.html.twig', ['users' => $users?? []]);
    }
}
