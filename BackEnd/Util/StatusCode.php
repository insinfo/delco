<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 26/07/2017
 * Time: 15:04
 */

namespace Delco\Util;

class StatusCode
{
    const ERROR = -1;
    const SUCCESS = 200;//ok
    const CREATED = 201;//Criado
    const ACCEPTED = 202;//Aceito
    const UNAUTHORIZED_INFORMATION = 203;//Informações não autorizadas
    const NO_CONTENT = 204;// No Content
    const BAD_REQUEST = 400;//A solicitação não pôde ser entendida devido à sintaxe mal formada.
    const UNAUTHORIZED = 401;//Não autorizado O pedido requer autenticação do usuário
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const REQUEST_TIMEOUT = 408;
    const INTERNAL_SERVER_ERROR = 500;//Internal Server Error
}