<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 16/08/2017
 * Time: 11:52
 * Utils is a module with some convenient utilities
 * Esta class implementa conexão e manipulação de banco de dados
 * através de interfaces do PDO que fornece uma camada de abstração de APIs de banco de dados
 * PDO(PHP Data Objects) é um módulo de PHP montado sob o paradigma Orientado a Objetos e cujo objetivo
 * é prover uma padronização da forma com que PHP se comunica com um banco de dados relacional.
 * Este módulo surgiu a partir da versão 5 de PHP. PDO, portanto, é uma interface que define um
 * conjunto de classes e a assinatura dos métodos de comunicação com uma base de dados.
 */
namespace Delco\Util;
use PDOException;
use PDO;
use Exception;
use Delco\Util\Exceptions\NoContentException;

class DBHandleStatic
{
    private static $CONNECTION = null;
    private static $SELECTED_CONNECTION_SETS = null;
    public static $JSON_FORMAT = 'json';
    public static $ARRAY_FORMAT = 'array';
    function __construct()
    {
    }
    public static function Connect($connectionName = DBConfig::DEFAULT_CONNECTION)
    {
        try
        {
            $CONNECTIONS = DBConfig::$CONNECTIONS['connections'];
            self::$SELECTED_CONNECTION_SETS = $CONNECTIONS[$connectionName];
            $DB_DRIVER = self::$SELECTED_CONNECTION_SETS['driver'];
            $DB_HOST = self::$SELECTED_CONNECTION_SETS['host'];
            $DB_NAME = self::$SELECTED_CONNECTION_SETS['database'];
            $DB_USERNAME = self::$SELECTED_CONNECTION_SETS['username'];
            $DB_PASSWORD = self::$SELECTED_CONNECTION_SETS['password'];
            $dsn = $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME;
            // $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
            self::$CONNECTION = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
        }
        catch (PDOException  $e)
        {
            throw new PDOException($e->getMessage(),$e->getCode(),$e);
        }
        self::$CONNECTION->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$CONNECTION;
    }
    //nome da tabela, campos da tabela,condições é uma array associativo ex('id' => 33), tipo de retono, opções estrax ex (limit 10), nome esquema, nome conexao
    public static function SelectAll($tableName, $fields=null, $conditions = null,$returnType = 'array',$advOptions = null, $schemaName = null, $connectionName = null)
    {
        $result = null;
        try
        {
            $conn = null;
            $rows = '*';
            $option = '';
            if ($connectionName == null)
            {
                $conn = self::Connect();
            }
            else
            {
                $conn = self::Connect($connectionName);
            }
            if ($schemaName == null)
            {
                $conn->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);
            }
            else
            {
                $conn->exec('SET search_path TO ' . $schemaName);
            }
            if($advOptions != null){
                $option = $advOptions;
            }
            if($fields != null)
            {
                $rows = self::implodeFields($fields);
            }
            $query = 'SELECT '.$rows.' FROM  "' . $tableName. '" '.$option;
            if($conditions != null)
            {
                $query = 'SELECT '.$rows.' FROM  "' . $tableName . '" WHERE ' .self::implodeConditions($conditions).' '.$option;
            }
            $select = $conn->prepare($query);
            $select->execute();
            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result =  json_encode($select->fetchAll(PDO::FETCH_ASSOC));
            }
            else
            {
                $result = $select->fetchAll(PDO::FETCH_CLASS, $returnType);
            }
            if ($select->rowCount() > 0)
            {
            }
            else
            {
                throw new NoContentException();
            }
        }
        catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(),$e->getCode(),$e);
        }
        return $result;
    }
    //nome da tabela, campos da tabela,condições é uma array associativo ex('id' => 33), tipo de retono, opções estrax ex (limit 10), nome esquema, nome conexao
    public static function Select($tableName, $fields=null, $conditions = null,$returnType = 'array',$advOptions = null, $schemaName = null, $connectionName = null)
    {
        $result = null;
        try
        {
            $conn = null;
            $rows = '*';
            $option = '';
            if ($connectionName == null)
            {
                $conn = self::Connect();
            }
            else
            {
                $conn = self::Connect($connectionName);
            }
            if ($schemaName == null)
            {
                $conn->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);
            }
            else
            {
                $conn->exec('SET search_path TO ' . $schemaName);
            }
            if($advOptions != null){
                $option = $advOptions;
            }
            if($fields != null)
            {
                $rows = self::implodeFields($fields);
            }
            $query = 'SELECT '.$rows.' FROM  "' . $tableName. '" '.$option;
            if($conditions != null)
            {
                $query = 'SELECT '.$rows.' FROM  "' . $tableName . '" WHERE ' .self::implodeConditions($conditions).' '.$option;
            }
            $select = $conn->prepare($query);
            $select->execute();
            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetch(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result =  json_encode($select->fetch(PDO::FETCH_ASSOC));
            }
            else
            {
                $result = $select->fetch(PDO::FETCH_CLASS, $returnType);
            }
            if ($select->rowCount() > 0)
            {
            }
            else
            {
                throw new NoContentException();
            }
        }
        catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(),$e->getCode(),$e);
        }
        return $result;
    }
    public static function Insert($tableName, $contentValues, $returnId = false, $tableIdSeq = null, $schemaName = null, $connectionName = null)
    {
        $result = null;
        try
        {
            if($returnId == true)
            {
                /*$result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result["employee_id"];
                $ret = $queryHandle->fetchColumn();*/;
            }
            $query = 'INSERT INTO "' . $tableName . '" (' . $contentValues->getColumns() . ') VALUES (' . $contentValues->getValues() . ') ';
            $conn = null;
            if ($connectionName == null)
            {
                $conn = self::Connect();
            }
            else
            {
                $conn = self::Connect($connectionName);
            }
            if ($schemaName == null)
            {
                $conn->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);
            }
            else
            {
                $conn->exec('SET search_path TO ' . $schemaName);
            }
            try
            {
                $statement = $conn->prepare($query);
                //$conn->beginTransaction();
                $statement->execute();
                //$conn->commit();
                if($returnId == true)
                {
                    if (self::$SELECTED_CONNECTION_SETS['driver'] == 'pgsql')
                    {
                        if ($tableIdSeq == null)
                        {
                            $result = $conn->lastInsertId($tableName . '_id_seq');
                        }
                        else
                        {
                            $result = $conn->lastInsertId($tableIdSeq);
                        }
                    }
                    else
                    {
                        $result = $conn->lastInsertId();
                    }
                }
            }
            catch(PDOException $e)
            {
                //$conn->rollBack();
                throw new PDOException($e->getMessage(),$e->getCode(),$e);
            }
        }
        catch (Exception  $e)
        {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
        return $result;
    }
    public static function Update($tableName, $conditions, $contentValues, $schemaName = null,$connectionName = null)
    {
        try
        {
            $query = "UPDATE " . $tableName . ' SET  ' . $contentValues->getUpdateString() . ' WHERE ' . $conditions;
            $conn = null;
            if ($connectionName != null)
            {
                $conn = self::Connect($connectionName);
            }else {
                $conn = self::Connect();
            }
            if ($schemaName == null)
            {
                $conn->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);
            }
            else
            {
                $conn->exec('SET search_path TO ' . $schemaName);
            }
            $prepare = $conn->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível atualizar o registro!',400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
    }
    public static function Delete($tableName, $conditions, $cascade=false, $schemaName = null,$connectionName = null)
    {
        try
        {
            $cas = '';
            if($cascade)
            {
                $cas = ' CASCADE ';
            }
            $query = "DELETE FROM " . $tableName . ' WHERE ' . $conditions.' '.$cas;
            $conn = null;
            if ($connectionName != null)
            {
                $conn = self::Connect($connectionName);
            }
            else
            {
                $conn = self::Connect();
            }
            if ($schemaName == null)
            {
                $conn->exec('SET search_path TO ' . DBConfig::DEFAULT_SCHEMA_NAME);
            }
            else
            {
                $conn->exec('SET search_path TO ' . $schemaName);
            }
            $prepare = $conn->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível deletar o registro',400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
    }
    private static function implodeConditions($arrayAsoc,$equalOrlike = '=')
    {
        $i = 0;
        $stringTemp = '';
        $arraySize = count($arrayAsoc);
        $and = '';
        if($arraySize >= 1)
        {
            $and = ' AND ';
        }
        foreach ($arrayAsoc as $key => $value)
        {
            if ($i != ($arraySize - 1))
            {
                $stringTemp .= ' "'. $key . '" '.$equalOrlike.' '."'" . $value . "' ".$and;
            }
            else
            {
                $stringTemp .= ' "'. $key . '" '.$equalOrlike.' '."'" . $value . "' ";
            }
            $i++;
        }
        return $stringTemp;
    }
    private static function implodeFields($array)
    {
        $i = 0;
        $stringTemp = '';
        $arraySize = count($array);
        $and = '';
        if($arraySize >= 1)
        {
            $and = ' , ';
        }
        foreach ($array as $value)
        {
            if ($i != ($arraySize - 1))
            {
                $stringTemp .= '"' . $value . '" '.$and;
            }
            else
            {
                $stringTemp .= '"' . $value . '" ';
            }
            $i++;
        }
        return $stringTemp;
    }
}
