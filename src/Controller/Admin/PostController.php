<?php

namespace App\Controller\Admin;

use Throwable;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\PostModel;
use App\Model\MediaModel;
use InvalidArgumentException;
use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostController extends BaseController
{

    /**
     * @throws Throwable
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $query = "SELECT p.*, u.username, m.file_path as media
        FROM posts p 
        LEFT JOIN users u ON u.id = p.user_id
        LEFT JOIN media m ON m.id = p.media_id
        ORDER BY p.created_at DESC";

        $posts = $this->getDB()->query($query);

        return $this->render($response, 'Admin/Modules/Post/index.html.twig', [
            'posts' => $posts->fetchAll()
        ]);
    }

    public function create(Request $request, Response $response)
    {

        $errors = [];

        if ($request->getParsedBody()) {
            $postModel = new PostModel();
            try {
                $data = $postModel->validate($request->getParsedBody());
                $id = $postModel->create($data);
                return $response->withRedirect($this->getRouter()->pathFor('posts_update', ['id' => $id]));
            } catch (InvalidArgumentException $ex) {
                $errors[] = $ex->getMessage();
            }
        }

        return $this->render($response, 'Admin/Modules/Post/form.html.twig', [
            'errors' => $errors,
            'data' => $request->getParsedBody()
        ]);
    }

    public function update(Request $request, Response $response, array $args = []): ResponseInterface
    {

        $data = $this->getDB()->selectOnce('posts', ['id' => $args['id']]);

        $errors = [];

        if ($request->getParsedBody()) {
            $postModel = new PostModel();
            $mediaModel = new MediaModel();
            try {

                $media = $mediaModel->validate($_FILES['file'] ?? null);
                $mediaId = $mediaModel->create($media);

                $body = $postModel->validate($request->getParsedBody());
                $postModel->update(array_merge(['media_id' => $mediaId], $body), ['id' => $args['id']]);

                return $response->withRedirect($this->getRouter()->pathFor('posts_update', ['id' => $args['id']]));
            } catch (InvalidArgumentException $ex) {
                $errors[] = $ex->getMessage();
            }
        }

        return $this->render($response, 'Admin/Modules/Post/form.html.twig', [
            'errors' => $errors,
            'data' => array_replace($data, $request->getParsedBody() ?? [])
        ]);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $postModel = new PostModel();
        $postModel->delete(['id' => $args['id']]);
    }

    public function savePost($data)
    {

    }
}
