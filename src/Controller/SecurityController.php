<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\UserModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SecurityController extends BaseController
{

    public function getLogin(Request $request, Response $response): ResponseInterface
    {
        return $this->render($response, 'Admin/Modules/Security/login.html.twig');
    }

    public function postLogin(Request $request, Response $response): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (!isset($body['username'], $body['password'])) {
            return $response->withStatus(400)->withJson(['error' => 'Missing required parameters']);
        }

        $userModel = new UserModel();

        $user = $userModel->selectOnce(['username' => $body['username']]);

        if (!$user) {
            return $response->withStatus(401)->withJson(['error' => 'Bad credentials']);
        }

        if (!password_verify($body['password'], $user['password'])) {
            return $response->withStatus(401)->withJson(['error' => 'Bad credentials']);
        }

        $_SESSION['user'] = $user;

        return $response->withRedirect($this->getRouter()->pathFor('posts_list'));
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $body = $request->getParsedBody();

        $userModel = new UserModel();
        try {
            $data = $userModel->validate($body);
            $userModel->create($data);
        } catch (\InvalidArgumentException $ex) {
            return $response->withStatus($ex->getCode())->withJson(['error' => $ex->getMessage()]);
        }

        return $response->withRedirect($this->getRouter()->pathFor('security_login'));
    }

    public function logout(Request $request, Response $response): Response
    {
        unset($_SESSION['user']);

        return $response->withRedirect($this->getRouter()->pathFor('security_login'));
    }

}
