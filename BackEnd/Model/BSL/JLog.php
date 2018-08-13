<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 20/03/2018
 * Time: 11:03
 */

namespace Delco\Model\BSL;

use Delco\Util\DBLayer;
use Delco\Util\DBConfig;
use \PDO;
use \Exception;

class JLog
{
    private $id;
    private $rota;
    private $rotaTipo;
    private $data;
    private $idSistema;
    private $idPessoa;
    private $idOrganograma;
    private $idPerfil;
    private $userName;
    private $ipPublico;
    private $ipPrivado;
    private $ipVisivelNoServidor;
    private $mensagem;
    private $tag;
    private $request;
    private $response;
    private $userAgent;
    private $domain;

    /*public static function e($message = '', $tag = '')
    {
        self::writeLog($message, $tag);
    }*/

    public static function write($rota, $rotaTipo, $idSistema, $idPessoa, $idOrganograma,
                                 $idPerfil, $userName, $ipPublico, $ipPrivado, $ipVisivelNoServidor,
                                 $mensagem = '', $tag = '', $request = '{}', $response = '{}', $userAgent = '', $domain = ''
    )
    {
        //$users = DB::connection('mysql2')->table('users')->get();
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date('d/m/Y H:i:s', time());
        //Middleware
        $log = [
            'rota' => $rota,
            'rotaTipo' => $rotaTipo,
            'idSistema' => $idSistema,
            'idPessoa' => $idPessoa,
            'idOrganograma' => $idOrganograma,
            'idPerfil' => $idPerfil,
            'userName' => $userName,
            'ipPublico' => $ipPublico,
            'ipPrivado' => $ipPrivado,
            'ipVisivelNoServidor' => $ipVisivelNoServidor,
            'mensagem' => $mensagem,
            'tag' => $tag,
            'request' => $request,
            'response' => $response,
            'userAgent' => $userAgent,
            'domain' => $domain,
        ];
        //DBLayer::Connect();
        //DBLayer::table('logs')->insert($log);

        $DB_DRIVER = 'pgsql';
        $DB_HOST = '192.168.133.12';
        $DB_NAME = 'sistemas';
        $DB_USERNAME = 'sisadmin';
        $DB_PASSWORD = 's1sadm1n';
        $dsn = $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME;

        $connection = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$connection->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);

        $query = ' INSERT INTO jubarte.logs ' . self::arrayToSqlCol(array_keys($log), $connection) . self::arrayToSqlValues(array_values($log), $connection);
        $statement = $connection->prepare($query);
        $statement->execute();
    }

    public static function arrayToSqlValues($array, \PDO $pdo)
    {
        $sql = "";
        foreach ($array as $val) {
            //adding value
            if ($val === NULL)
                $sql .= "NULL";
            else
                /*
                useless piece of code see comments
                if($val === FALSE)
                   $sql .= "FALSE";
                else
                */
                $sql .= "" . $pdo->quote($val) . "";

            $sql .= ", ";
        };

        return " VALUES(" . rtrim($sql, " ,") . ") ";
    }

    public static function arrayToSqlCol($array, \PDO $pdo)
    {
        $sql = "";
        foreach ($array as $val) {
            $sql .= '"' . $val . '"';
            $sql .= ", ";
        };

        return " (" . rtrim($sql, " ,") . ") ";
    }
}