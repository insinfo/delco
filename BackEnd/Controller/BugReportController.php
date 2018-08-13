<?php

namespace Delco\Controller;


use \Slim\Http\Request;
use \Slim\Http\Response;
use \Exception;

use Delco\Util\DBLayer;
use Delco\Util\Utils;
use Delco\Model\VO\BugReport;

use Delco\Util\StatusCode;
use Delco\Util\StatusMessage;
use Delco\Model\BSL\SendMail;

class BugReportController
{
    public static function getAll(Request $request, Response $response)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $draw = $parametros['draw'];
            $limit = $parametros['length'];
            $offset = $parametros['start'];
            $search = '%' . $parametros['search'] . '%';

            $query = DBLayer::Connect()->table(BugReport::TABLE_NAME)
                ->where(function ($query) use ($request, $search)
            {
                $query->orWhere(BugReport::KEY_ID, DBLayer::OPERATOR_ILIKE, $search);
                $query->orWhere(BugReport::SISTEMA, DBLayer::OPERATOR_ILIKE, $search);

            })
            ;
            $totalRecords = $query->count();
            if ($parametros)
            {
                $data = $query->orderBy(BugReport::KEY_ID, DBLayer::ORDER_DIRE_ASC)
                    ->take($limit)->offset($offset)->get();
            }
            else
            {
                $data = $query->orderBy(BugReport::KEY_ID, DBLayer::ORDER_DIRE_ASC)
                    ->get();
            }
            $result['draw'] = $draw;
            $result['recordsFiltered'] = $totalRecords;
            $result['recordsTotal'] = $totalRecords;
            $result['data'] = $data;
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
    }

    public static function save(Request $request, Response $response)
    {
        try
        {
            $id = $request->getAttribute('id');
            $formData = Utils::filterArrayByArray($request->getParsedBody(), BugReport::TABLE_FIELDS);

            $sendMail = new SendMail();
            $sendMail->to("BugReporte ".$formData[BugReport::SISTEMA],$formData[BugReport::DESCRICAO_PROBLEMA]);

            if ($id)
            {
                DBLayer::Connect()->table(BugReport::TABLE_NAME)
                    ->where(BugReport::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)
                    ->update($formData);
            }
            else
            {
                DBLayer::Connect()->table(BugReport::TABLE_NAME)->insert($formData);
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson((['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]));
        }
        return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::MENSAGEM_DE_SUCESSO_PADRAO]);
    }

    public static function get(Request $request, Response $response)
    {
        try
        {
            $id = $request->getAttribute('id');

            $result = DBLayer::Connect()->table(BugReport::TABLE_NAME)
                ->where(BugReport::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)
                ->first();

            return $response->withStatus(StatusCode::SUCCESS)->withJson($result);
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    public static function delete(Request $request, Response $response)
    {
        try
        {
            $formData = $request->getParsedBody();
            $ids = $formData['ids'];
            $idsCount = count($ids);
            $itensDeletadosCount = 0;
            foreach ($ids as $id)
            {
                $result = DBLayer::Connect()->table(BugReport::TABLE_NAME)
                    ->where(BugReport::KEY_ID, DBLayer::OPERATOR_EQUAL, $id)
                    ->first();

                if ($result)
                {
                    if (DBLayer::Connect()->table(BugReport::TABLE_NAME)->delete($id))
                    {
                        $itensDeletadosCount++;
                    }
                }
            }
            if ($itensDeletadosCount == $idsCount)
            {
                return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::TODOS_ITENS_DELETADOS]);
            }
            else if ($itensDeletadosCount > 0)
            {
                return $response->withStatus(StatusCode::SUCCESS)->withJson(['message' => StatusMessage::NEM_TODOS_ITENS_DELETADOS]);
            }
            else
            {
                return $response->withStatus(StatusCode::SUCCESS)->withJson((['message' => StatusMessage::NENHUM_ITEM_DELETADO]));
            }
        }
        catch (Exception $e)
        {
            return $response->withStatus(StatusCode::BAD_REQUEST)->withJson(['message' => StatusMessage::MENSAGEM_ERRO_PADRAO, 'exception' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }



}