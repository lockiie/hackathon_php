<?php

declare(strict_types=1);

namespace Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fig\Http\Message\StatusCodeInterface;
use Api\App\JsonResponse;

use Api\Models\Categoria;
use Api\Factorys\QueryFactory;

class CategoriaController
{
    private $query;

    public function __construct()
    {
        $this->query = QueryFactory::getIstance();
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $filterCategoria = $request->getQueryParams()['categoria'] ?? null;
        $sql = 'select * from categoria';
        if (!is_null($filterCategoria)) {
            $sql = "select * from categoria where categoria like '%$filterCategoria%'";
        }

        $categorias= $this->query->executeSql($sql, Categoria::class);

        if (is_null($categorias)) {
            return JsonResponse::create(
                $response,
                ['message' => 'Categoria não encontrada'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return JsonResponse::create(
            $response,
            $categorias,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {
        $categoriaRequest = json_decode($request->getBody()->getContents());

        $categoria = new Categoria;
        $categoria->categoria = $categoriaRequest->categoria;

        $id = $this->query->insert($categoria);

        $newCategoria = $this->query->find($id, Categoria::class);

        return JsonResponse::create(
            $response,
            $newCategoria,
            StatusCodeInterface::STATUS_CREATED
        );
    }

    public function show(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $categoria = $this->query->find($id, Categoria::class);
        if (is_null($categoria)) {
            return JsonResponse::create(
                $response,
                ['message' => 'categoria não encontrada'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return JsonResponse::create(
            $response,
            $categoria,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];

        $categoria= $this->query->find($id, Categoria::class);
        if (is_null($categoria)) {
            return $response->withStatus(404);
        }

        $categoriaRequest = json_decode($request->getBody()->getContents());

        $categoria->categoria = $categoriaRequest->categoria;

        $this->query->update($categoria);

        $newCategoria= $this->query->find($id, Categoria::class);

        return JsonResponse::create(
            $response,
            $newCategoria,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $categoria= $this->query->find($args['id'], Categoria::class);
        if (is_null($categoria)) {
            return JsonResponse::create(
                $response,
                ['message' => 'categoria não existe'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }
        $this->query->delete($categoria);
        return JsonResponse::create(
            $response,
            ['success' => true],
            StatusCodeInterface::STATUS_NO_CONTENT
        );
    }
}
