<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 07/12/2017
 * Time: 11:27
 */

namespace Delco\Controller;

use \Firebase\JWT\ExpiredException;
use \Exception;
use \Slim\Http\Request;
use \Slim\Http\Response;

use Delco\Util\DBLayer;
use Delco\Util\LdapAuth;
use Delco\Util\JWTWrapper;
use Delco\Util\StatusCode;
use Delco\Util\StatusMessage;
use Delco\Util\Criptografia;
use Delco\Model\BSL\JLog;

class AuthController
{
    public static function authenticate(Request $request, Response $response)
    {
        try {

            // obter somente username and senha da requisição
            //$credentials = $request->only('userName', 'password');
            $param = $request->getParsedBody();
            $loginName = $param['userName'];
            $password = $param['password'];
            $ipClientPrivate = $param['ipPrivado'];
            $ipClientPublic = $param['ipPublico'];
            $ipClientVisibleByServer = $request->getAttribute('ip_address');
            $pws = Criptografia::encrypt($password,Criptografia::$key);
            $method = $request->getMethod();
            $userAgent = $request->getHeaderLine('User-Agent');
            $origin = $request->getHeaderLine('Origin');
            $host = $request->getHeaderLine('Host');
            //$route = $request->getUri();
            //$route = $request->getAttribute('route');
            $route = $request->getUri()->getPath();
            //$expirationSec = 32400; //9 horas $request->isGet()
            $expirationSec = 32400; //32400 segundo = 9 horas

            //grava o log antes da autenticação
            JLog::write(
                $route,
                $method,
                3,
                null,
                null,
                null,
                $loginName,
                $ipClientPublic,
                $ipClientPrivate,
                $ipClientVisibleByServer,
                'pre login na jubarte',
                'login',
                '{}',
                '{}',
                $userAgent,
                $host
            );

            $ldapAuth = new LdapAuth();
            $ldapAuth->setHost('ldap://192.168.133.10');
            $ldapAuth->setDomain('DC=dcro,DC=gov');
            $ldapAuth->setUserDomain('@dcro.gov');

            if ($ldapAuth->authenticate($loginName, $password))
            {
               /* $usuarioDAL = new UsuarioDAL();

                if ($usuarioDAL->checkAuth($loginName))
                {
                    $usuario = $usuarioDAL->getByLoginName($loginName);
                    $pessoaDAL = new PessoaDAL();
                    $pessoa = $pessoaDAL->getById($usuario->getIdPessoa());

                    // autenticacao valida, gerar token
                    $jwt = JWTWrapper::encode(['expirationSec' => $expirationSec,
                        'domain' => 'jubarte.riodasostras.rj.gov.br',
                        'userdata' => [
                            'idSistema' => 1,
                            'idPessoa' => $usuario->getIdPessoa(),
                            'idOrganograma' => $usuario->getIdOrganograma(),
                            'loginName' => $loginName,
                            'idPerfil' => $usuario->getIdPessoa(),
                            'pws' => $pws,
                            'ipClientPrivate' => $ipClientPrivate,
                            'ipClientPublic' => $ipClientPublic,
                            'ipClientVisibleByServer' => $ipClientVisibleByServer,
                            'host' => $host,
                            'origin' =>  $origin,
                            'userAgent' => $userAgent
                        ]
                    ]);

                    return $response->withStatus(StatusCode::SUCCESS)->
                    withJson([
                        'expiresIn' => $expirationSec,
                        'accessToken' => $jwt,
                        'fullName' => $pessoa->getNome(),
                        'loginName' => $loginName,
                        'idPessoa' =>  $usuario->getIdPessoa(),
                        'idOrganograma' => $usuario->getIdOrganograma(),
                        'idPerfil' => $usuario->getIdPessoa()
                    ]);
                }*/
            }
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::UNAUTHORIZED)->
            withJson(['message' => StatusMessage::CREDENCIAL_INVALIDA, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
        return $response->withStatus(StatusCode::UNAUTHORIZED)->
        withJson(['message' => StatusMessage::CREDENCIAL_INVALIDA]);
    }

    public static function checkToken(Request $request, Response $response)
    {
        try {
            $param = $request->getParsedBody();
            $token = $param['access_token'];
            if ($token) {
                JWTWrapper::decode($token);
                return $response->withStatus(StatusCode::SUCCESS)->
                withJson(['login' => true, 'message' => StatusMessage::ACESSO_AUTORIZADO]);
            }
        } catch (ExpiredException $e) {   //token espirou
            $response->withStatus(StatusCode::UNAUTHORIZED)->
            withJson(['message' => StatusMessage::CREDENCIAL_INVALIDA, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        } catch (Exception $e) {
            return $response->withStatus(StatusCode::UNAUTHORIZED)->
            withJson(['message' => StatusMessage::CREDENCIAL_INVALIDA, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
        return $response->withStatus(StatusCode::UNAUTHORIZED)->
        withJson(['message' => StatusMessage::CREDENCIAL_INVALIDA]);
    }

}