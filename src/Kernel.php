<?php

namespace App;

use PDO;
use Slim\App;
use Throwable;
use Dotenv\Dotenv;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Http\Environment;
use App\Services\Database;
use Slim\Views\TwigExtension;
use App\Middleware\AuthMiddleware;
use Psr\Container\ContainerInterface;
use App\Controller\SecurityController;
use App\Controller\HomepageController;
use App\Controller\Admin\PostController;
use App\Controller\Admin\HomeController;

class Kernel
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Kernel
     */
    private static $instance;

    public static function getInstance(): Kernel
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function configure(): Kernel
    {

        $dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotEnv->load();

        $dotEnv->required(['DB_DSN', 'DB_USER']);

        $this->app = new App(['settings' => [
            'displayErrorDetails' => $_ENV['DEBUG'] ?? false,
            'viewTemplatesDirectory' => __DIR__ . '/../templates',
        ]]);

        $this->initDependencies();
        $this->initMiddleware();
        $this->initRoutes();

        return $this;
    }

    public function run()
    {
        try {
            $this->app->run();
        } catch (Throwable $e) {

        }
    }

    private function initDependencies()
    {
        $this->container = $this->app->getContainer();

        $this->container['view'] = function ($container) {

            $router = $container->get('router');

            $view = new Twig(__DIR__ . '/../templates', [
                'debug' => true,
                'charset' => 'UTF-8',
                'strict_variables' => true,
                'autoescape' => 'html',
                'auto_reload' => true,
                'cache' => __DIR__ . '/../app/cache'
            ]);

            $uri = Uri::createFromEnvironment(new Environment($_SERVER));
            $view->addExtension(new TwigExtension($router, $uri));

            return $view;
        };

        $this->container['db'] = new Database($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $this->container['authMiddleware'] = function ($container) {
            return new AuthMiddleware($container->get('router'));
        };
    }

    private function initMiddleware()
    {
    }

    private function initRoutes()
    {
        $this->app->get('/', HomepageController::class)->setName('homepage');

        $this->app->get('/admin', HomeController::class)->setName('admin_homepage');

        $this->app->get('/security/login', SecurityController::class . ':getLogin')
            ->setName('security_login');

        $this->app->post('/security/login', SecurityController::class . ':postLogin');

        $this->app->map(['GET', 'POST'], '/security/register', SecurityController::class . ':register')
            ->setName('security_register');

        $this->app->get('/security/logout', SecurityController::class . ':logout')
            ->setName('security_logout');

        $this->app->group('/admin', function (App $app) {
            $app->group('/posts', function (App $app) {
                $app->get('', PostController::class . ':index')->setName('posts_list');
                $app->map(['GET', 'POST'], '/create', PostController::class . ':create')->setName('posts_create');
                $app->map(['GET', 'POST'], '/{id}', PostController::class . ':update')->setName('posts_update');
                $app->delete('/{id}', PostController::class . ':delete')->setName('posts_delete');
            });
            $app->group('/users', function (App $app) {
                $app->get('/', PostController::class . ':index')->setName('users_list');
                $app->post('/', PostController::class . ':create')->setName('users_create');
                $app->put('/{id}', PostController::class . ':update')->setName('users_update');
                $app->delete('/{id}', PostController::class . ':delete')->setName('users_delete');
            });
        })->add($this->container->get('authMiddleware'));
    }
}
