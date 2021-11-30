<?php

declare(strict_types=1);

namespace Api\App;

use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Api\Controllers\CategoriaController;
use Api\Controllers\EmpresaController;
use Api\Controllers\InfoController;
use Api\Controllers\ProdutoController;

use Api\Middlewares\LogMiddleware;
use Api\Middlewares\CacheMiddleware; 

class Routes
{
    private static $app;

    public static function create(): App
    {
        self::$app = AppFactory::create();

        self::$app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        self::$app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });

        self::$app->group('/', function (RouteCollectorProxy $group): void {
            $group->get('', InfoController::class . ':home');
            $group->get('server', InfoController::class . ':server');
        })->add(new CacheMiddleware())
          ->add(new LogMiddleware());

        self::$app->group('/api/categorias', function (RouteCollectorProxy $group): void {
            $group->get('', CategoriaController::class . ':index')->add(new CacheMiddleware());
            $group->post('', CategoriaController::class . ':create');
            $group->get('/{id}', CategoriaController::class . ':show');
            $group->put('/{id}', CategoriaController::class . ':update');
            $group->delete('/{id}', CategoriaController::class . ':delete');
        })->add(new LogMiddleware());

        self::$app->group('/api/empresas', function (RouteCollectorProxy $group): void {
            $group->get('', EmpresaController::class . ':index')->add(new CacheMiddleware());
            $group->post('', EmpresaController::class . ':create');
            $group->get('/{id}', EmpresaController::class . ':show');
            $group->put('/{id}', EmpresaController::class . ':update');
            $group->delete('/{id}', EmpresaController::class . ':delete');
        })->add(new LogMiddleware());

        self::$app->group('/api/produtos', function (RouteCollectorProxy $group): void {
            $group->get('', ProdutoController::class . ':index')->add(new CacheMiddleware());
            $group->post('', ProdutoController::class . ':create');
            $group->get('/{id}', ProdutoController::class . ':show');
            $group->put('/{id}', ProdutoController::class . ':update');
            $group->delete('/{id}', ProdutoController::class . ':delete');
        })->add(new LogMiddleware());

        return self::$app;
    }
}
