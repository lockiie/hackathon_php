<?php

declare(strict_types=1);

namespace Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fig\Http\Message\StatusCodeInterface;
use Api\App\JsonResponse;

use Api\Models\Empresa;
use Api\Factorys\QueryFactory;


class EmpresaController
{
    private $query;

    public function __construct()
    {
        $this->query = QueryFactory::getIstance();
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $filterEmpresa = $request->getQueryParams()['empresa'] ?? null;
        $sql = 'select * from empresa';
        if (!is_null($filterEmpresa)) {
            $sql = "select * from empresa where empresa like '%$filterEmpresa%'";
        }

        $empresas= $this->query->executeSql($sql, Empresa::class);

        if (is_null($empresas)) {
            return JsonResponse::create(
                $response,
                [],
                StatusCodeInterface::STATUS_OK
            );
        }

        return JsonResponse::create(
            $response,
            $empresas,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {
        $empresaRequest = json_decode($request->getBody()->getContents());

        $empresa = new Empresa;
        $empresa->empresa = $empresaRequest->empresa;
        $empresa->whatsapp = $empresaRequest->whatsapp;

        $id = $this->query->insert($empresa);

        $newEmpresa= $this->query->find($id, Empresa::class);

        return JsonResponse::create(
            $response,
            $newEmpresa,
            StatusCodeInterface::STATUS_CREATED
        );
    }

    public function show(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $empresa= $this->query->find($id, Empresa::class);
        if (is_null($empresa)) {
            return JsonResponse::create(
                $response,
                ['message' => 'Empresa não encontrada'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        return JsonResponse::create(
            $response,
            $empresa,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];

        $empresa= $this->query->find($id, Empresa::class);
        if (is_null($empresa)) {
            return $response->withStatus(404);
        }

        $empresaRequest = json_decode($request->getBody()->getContents());

        $empresa->empresa = $empresaRequest->empresa;
        $empresa->whatsapp = $empresaRequest->whatsapp;

        $this->query->update($empresa);

        $newEmpresa= $this->query->find($id, Empresa::class);

        return JsonResponse::create(
            $response,
            $newEmpresa,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $empresa= $this->query->find($args['id'], Empresa::class);
        if (is_null($empresa)) {
            return JsonResponse::create(
                $response,
                ['message' => 'Empresa não encontrada'],
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }
        $this->query->delete($empresa);
        return JsonResponse::create(
            $response,
            ['success' => true],
            StatusCodeInterface::STATUS_NO_CONTENT
        );
    }
}
