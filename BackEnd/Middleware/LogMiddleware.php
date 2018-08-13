<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 20/03/2018
 * Time: 12:16
 */

namespace Delco\Middleware;

use Delco\Util\JWTWrapper;
use \Exception;
use Delco\Util\Utils;
use Delco\Model\VO\Token;
use Delco\Model\BSL\JLog;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Http\Response;

class LogMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try
        {
            $bearer = $request->getHeader('Authorization');
            $ipClientVisibleByServer = $request->getAttribute('ip_address');
            $method = $request->getMethod();
            $userAgent = $request->getHeaderLine('User-Agent');
            $origin = $request->getHeaderLine('Origin');
            //$host = $request->getUri()->getHost()
            $host = $request->getHeaderLine('Host');
            //$route = $request->getUri();
            //$route = $request->getAttribute('route');
            $route = $request->getUri()->getPath();

            //$method == 'PUT' || $method == 'DELETE'
            if ($method == 'PUT' || $method == 'DELETE')
            {
                $tokenInfo = new Token();
                if ($bearer) {

                    list($token) = sscanf($bearer[0], 'Bearer %s');
                    $jwt = JWTWrapper::decode($token);
                    $tokenInfo->fillFromJwt($jwt);
                }
                JLog::write(
                    $route,
                    $method,
                    $tokenInfo->getIdSistema(),
                    $tokenInfo->getIdPessoa(),
                    $tokenInfo->getIdOrganograma(),
                    $tokenInfo->getIdPerfil(),
                    $tokenInfo->getLoginName(),
                    $tokenInfo->getIpClientPublic(),
                    $tokenInfo->getIpClientPrivate(),
                    $ipClientVisibleByServer,
                    'rota interceptada pelo LogMiddleware no jubarte',
                    'info',
                    json_encode($request->getParsedBody()),
                    '{}',
                    $userAgent,
                    $request->getUri()->getHost()
                );
            }

        }
        catch (Exception $e) {

        }

        return $next($request, $response);
    }

    public function getUserAgent($request)
    {
        $user_agent = $request->header('User-Agent');
        $bname = 'Unknown';
        $platform = 'Unknown';

        //First get the platform?
        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }


        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $user_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $user_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        return $bname;
    }

}