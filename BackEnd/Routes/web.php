<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/', function (Request $request, Response $response, $args) use ($app)
{
    return $this->view->render($response, 'page-pesquisa-uab.php');

});


// ROTAS DE WEBPAGES
$app->group('/pages', function () use ($app)
{


});