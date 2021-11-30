<?php

declare(strict_types=1);

namespace Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fig\Http\Message\StatusCodeInterface;
use Api\App\JsonResponse;

use Api\Models\Produto;
use Api\Factorys\QueryFactory;

class ProdutoController
{
    private $query;

    public function __construct()
    {
        $this->query = QueryFactory::getIstance();
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $sql = 'select *
                from produto p
                where id = id';
        
        $filterProduto= $request->getQueryParams()['produto'] ?? null;

        if (!is_null($filterProduto)) {
            $sql = "$sql and produto like '%$filterProduto%'";
        }

        $filterDescricao= $request->getQueryParams()['descricao'] ?? null;

        if (!is_null($filterDescricao)) {
            $sql = "$sql and descricao like '%$filterDescricao%'";
        }

        $filterValorMenor= $request->getQueryParams()['valor_menor'] ?? null;

        if (!is_null($filterValorMenor)) {
            $sql = "$sql and valor < $filterValorMenor";
        }

        $filterValorMaior= $request->getQueryParams()['valor_maior'] ?? null;

        if (!is_null($filterValorMaior)) {
            $sql = "$sql and valor > $filterValorMaior";
        }

        $filterCategoria_ID= $request->getQueryParams()['categoria_id'] ?? null;

        if (!is_null($filterCategoria_ID)) {
            $sql = "$sql and categoria_id = $filterCategoria_ID";
        }

        $filterEmpresa_ID= $request->getQueryParams()['empresa_id'] ?? null;

        if (!is_null($filterEmpresa_ID)) {
            $sql = "$sql and empresa_id = $filterEmpresa_ID";
        }
        $produtos= $this->query->executeSql($sql, Produto::class);


        if (is_null($produtos)) {
            return JsonResponse::create(
                $response,
                ['message' => 'Produto não encontrado'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return JsonResponse::create(
            $response,
            $produtos,
            StatusCodeInterface::STATUS_OK
        );

    }

    public function create(Request $request, Response $response, $args): Response
    {
        $produtoRequest = json_decode($request->getBody()->getContents());

        $produto= new Produto;
        $produto->produto = $produtoRequest->produto;
        $produto->foto = $produtoRequest->foto;
        $produto->descricao = $produtoRequest->descricao;
        $produto->valor = $produtoRequest->valor;
        $produto->categoria_id = $produtoRequest->categoria_id;
        $produto->empresa_id = $produtoRequest->empresa_id;

        $id = $this->query->insert($produto);

        $newProduto = $this->query->find($id, Produto::class);

        return JsonResponse::create(
            $response,
            $newProduto,
            StatusCodeInterface::STATUS_CREATED
        );
    }

    public function show(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $produto= $this->query->find($id, Produto::class);
        if (is_null($produto)) {
            return JsonResponse::create(
                $response,
                ['message' => 'Produto não encontrado'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return JsonResponse::create(
            $response,
            $produto,
            StatusCodeInterface::STATUS_OK
        );
    }
    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];

        $produto= $this->query->find($id, Produto::class);
        if (is_null($produto)) {
            return $response->withStatus(404);
        }

        $produtoRequest = json_decode($request->getBody()->getContents());

        $produto->produto = $produtoRequest->produto;
        $produto->foto = $produtoRequest->foto;
        $produto->descricao = $produtoRequest->descricao;
        $produto->valor = $produtoRequest->valor;
        $produto->categoria_id = $produtoRequest->categoria_id;
        $produto->empresa_id = $produtoRequest->empresa_id;

        $this->query->update($produto);

        $newProduto = $this->query->find($id, Product::class);

        return JsonResponse::create(
            $response,
            $newProduto,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $produto= $this->query->find($args['id'], Produto::class);
        if (is_null($produto)) {
            return JsonResponse::create(
                $response,
                ['message' => 'produto não existe'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }
        $this->query->delete($produto);
        return JsonResponse::create(
            $response,
            ['success' => true],
            StatusCodeInterface::STATUS_NO_CONTENT
        );
    }
}
