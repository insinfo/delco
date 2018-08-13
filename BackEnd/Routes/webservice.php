<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use Portal\Controller\PesquisaUABController;
use Portal\Controller\LogController;

// ROTAS DE WEBSERVICE REST
$app->group('/api', function () use ($app)
{
    //**************** ROTAS PESQUISA UAB ******************/

    //OBTEM UM REGISTRO
    $app->get('/pesquisa/uab/[{id}]', function (Request $request, Response $response, $args) use ($app)
    {
        return '';
    });
    //CRIA E ATUALIZA REGISTRO
    $app->put('/pesquisa/uab/[{id}]', function (Request $request, Response $response, $args) use ($app)
    {
        return PesquisaUABController::save($request,$response);
    });
    //LISTA SISTEMA REGISTROS
    $app->post('/pesquisa/uab',function (Request $request, Response $response, $args) use ($app)
    {
        return PesquisaUABController::getAll($request,$response);
    });
    //DELETA REGISTRO
    $app->delete('/pesquisa/uab',function (Request $request, Response $response, $args) use ($app)
    {
        return '';
    });
    //Logs de Login do Site da Prefeitura
    $app->put('/logs',function (Request $request, Response $response, $args) use ($app)
    {
        return LogController::save($request,$response);
    });

});//->add( new AuthMiddleware() )->add( new LogMiddleware() );