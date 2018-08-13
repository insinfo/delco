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

class PesquisaUABController
{
    public static function getAll(Request $request, Response $response)
    {
        try {

            $params = $request->getParsedBody();
            $draw = isset($params['draw']) ? $params['draw'] : null;
            $limit = isset($params['length']) ? $params['length'] : null;
            $offset = isset($params['start']) ? $params['start'] : null;
            $search =  isset($params['search']) ? '%' . $params['search'] . '%' : null;

            DBLayer::Connect();
            $query = DBLayer::table(PesquisaUAB::TABLE_NAME);

            $totalRecords = $query->count();

            if($limit && $offset)
            {
                $data = $query->limit($limit)->offset($offset)->get();
            }
            else
            {
                $data = $query->get();
            }

            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;

            return $response->withStatus(StatusCode::SUCCESS)
                ->withJson($result);

        } catch (Exception $e) {
            return $response->withStatus(StatusCode::BAD_REQUEST)
                ->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO,
                    'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }


    }

    public static function save(Request $request, Response $response)
    {
        try
        {
            $id = $request->getAttribute('id');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), PesquisaUAB::TABLE_FIELDS);

            if ($id)
            {
                DBLayer::Connect()->table(PesquisaUAB::TABLE_NAME)
                    ->where(PesquisaUAB::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)
                    ->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(PesquisaUAB::TABLE_NAME)->insert($formData);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

}