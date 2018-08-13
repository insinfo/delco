<?php

namespace Delco\Middleware;//Middleware;

//use \Slim\Http\Request;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Http\Response;

use \Firebase\JWT\ExpiredException;
use Delco\Util\JWTWrapper;
use \Exception;

use Delco\Util\StatusCode;
use Delco\Util\StatusMessage;

class AuthMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try
        {
            $bearer = $request->getHeader('Authorization');
            $ipClientVisibleByServer = $request->getAttribute('ip_address');

            if ($bearer)
            {
                list($token) = sscanf($bearer[0], 'Bearer %s');
                $isDecode = JWTWrapper::decode($token);

                $tokenIp = $isDecode->data->ipClientVisibleByServer;

                /*if($tokenIp != $ipClientVisibleByServer)
                {
                    throw new Exception('IP diferente ipToken:' .$tokenIp .' ip:'.$ipClientVisibleByServer);
                }*/

                $request = $request->withAttribute('jwt',$isDecode);
            }
            else
            {
                // nao foi possivel extrair token do header Authorization
                return $response->withStatus(StatusCode::UNAUTHORIZED)
                    ->withJson([
                        'message' => 'Acesso não Autorizado!',
                        'exception' => 'Header sem Token'
                    ]);
            }
        }
        catch (ExpiredException $e)
        {  //  token espirou
            return $response->withStatus(StatusCode::UNAUTHORIZED)
                ->withJson(['message' => 'Acesso não Autorizado!', 'exception' => 'token espirou']);
        }
        catch (Exception $e)
        {  // nao foi possivel decodificar o token jwt
            return $response->withStatus(StatusCode::UNAUTHORIZED)
                ->withJson(['message' => 'Acesso não Autorizado!', 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }

        return $next($request, $response);
    }
}
