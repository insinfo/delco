<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 08/05/2018
 * Time: 12:35
 */

namespace Delco\Controller;

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Exception;

use Delco\Util\DBLayer;
use Delco\Util\Utils;
use Delco\Model\VO\PesquisaUAB;
use Delco\Util\StatusCode;
use Delco\Util\StatusMessage;

class LogController
{
    public static function save(Request $request, Response $response)
    {
        try
        {
            $formData = $request->getParsedBody();
            $formData['userAgent'] = $request->getHeaderLine('User-Agent');
            $formData['request'] = self::recursiveImplode($request->getHeaders(), true);
                DBLayer::Connect()->table('logs')->insert($formData);

        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

    public static function recursiveImplode(array $array, $glue = ',', $include_keys = false, $trim_all = true)
    {
        $glued_string = '';
        // Recursively iterates array and adds key/value to glued string
        array_walk_recursive($array, function($value, $key) use ($glue, $include_keys, &$glued_string)
        {
            $include_keys and $glued_string .= $key.$glue;
            $glued_string .= $value.$glue;
        });
        // Removes last $glue from string
        strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));
        // Trim ALL whitespace
        $trim_all and $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);
        return (string) $glued_string;
    }

}