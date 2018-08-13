<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 14/07/2017
 * Time: 18:06
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

class DBHandle
{
    private $SELECTED_CONNECTION_SETS = null;
    private $connection = null;
    public static $JSON_FORMAT = 'json';
    public static $ARRAY_FORMAT = 'array';

    function __construct()
    {
    }

    public function Connect($connectionName = DBConfig::DEFAULT_CONNECTION)
    {
        try
        {
            $CONNECTIONS = DBConfig::$CONNECTIONS['connections'];
            $this->SELECTED_CONNECTION_SETS = $CONNECTIONS[$connectionName];
            $DB_DRIVER = $this->SELECTED_CONNECTION_SETS['driver'];
            $DB_HOST = $this->SELECTED_CONNECTION_SETS['host'];
            $DB_NAME = $this->SELECTED_CONNECTION_SETS['database'];
            $DB_USERNAME = $this->SELECTED_CONNECTION_SETS['username'];
            $DB_PASSWORD = $this->SELECTED_CONNECTION_SETS['password'];

            $dsn = $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME;

            // $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
            $this->connection = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->SetScheme(DBConfig::DEFAULT_SCHEMA_NAME);
        }
        catch (PDOException  $e)
        {
            throw new PDOException($e);
        }
    }

    public function SetScheme($schemaName)
    {
        if ($this->connection != null)
        {
            $this->connection->exec('SET search_path TO ' . $schemaName);
        }
    }

    public function Begin()
    {
        $this->connection->beginTransaction();
    }

    public function End()
    {
        $this->connection->commit();
    }

    public function RollBack()
    {
        $this->connection->rollBack();
    }

    //nome da tabela, campos da tabela,condições é uma array associativo ex('id' => 33), tipo de retono, opções estrax ex (limit 10)
    public function SelectAll($tableName, $fields = null, $conditions = null, $returnType = 'array', $advOptions = null)
    {
        $result = null;
        try
        {
            $rows = '*';
            $option = '';

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            if ($advOptions != null)
            {
                $option = $advOptions;
            }

            if ($fields != null)
            {
                $rows = $this->implodeFields($fields);
            }

            $query = 'SELECT ' . $rows . ' FROM  "' . $tableName . '" ' . $option;

            if ($conditions != null)
            {
                $query = 'SELECT ' . $rows . ' FROM  "' . $tableName . '" WHERE ' . $this->implodeConditions($conditions) . ' ' . $option;
            }

            $select = $this->connection->prepare($query);
            $select->execute();

            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result = json_encode($select->fetchAll(PDO::FETCH_ASSOC));
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
            throw new PDOException($e);
        }
        return $result;
    }

    public function SelectAllAdvanced($tableName, $fields = null, $conditions = null, $conditionsType = '=', $sqlFunctionForLike = null, $returnType = 'array', $limit = null, $offset = 0, $orderBy = null, $rawSQL = null)
    {
        $result = null;
        try
        {
            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            $columns = '*';
            $where = '';
            $order = '';
            $lim = '';
            $raw = '';

            if ($fields != null)
            {
                $columns = $this->implodeFields($fields);
            }

            if ($conditions != null)
            {
                $where = ' WHERE ' . $this->implodeConditions($conditions, $conditionsType, $sqlFunctionForLike) . ' ';
            }

            if ($orderBy != null)
            {
                $order = ' ORDER BY ' . $orderBy . ' ASC ' . ' ';
            }
            if ($limit != null)
            {
                $lim = ' LIMIT ' . $limit . ' OFFSET ' . $offset . ' ';
            }

            if ($rawSQL != null)
            {
                $raw = $rawSQL;
            }

            $query = 'SELECT ' . $columns . ' FROM  "' . $tableName . '" ' . $where . $order . $lim . $raw;
            //echo $query;
            $select = $this->connection->prepare($query);
            $select->execute();

            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result = json_encode($select->fetchAll(PDO::FETCH_ASSOC));
            }
            else
            {
                $result = $select->fetchAll(PDO::FETCH_CLASS, $returnType);
            }

            if (!($select->rowCount() > 0))
            {
                return null;
            }
        }
        catch (PDOException $e)
        {
            throw new PDOException($e);
        }
        return $result;
    }

    public function SelectRAW($query, $returnType = 'array', $multiOrSingle = 'multi')
    {
        $result = null;

        if ($this->connection == null)
        {
            throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
        }

        $select = $this->connection->prepare($query);
        $select->execute();

        if ($multiOrSingle == 'multi')
        {
            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result = json_encode($select->fetchAll(PDO::FETCH_ASSOC));
            }
            else
            {
                $result = $select->fetchAll(PDO::FETCH_CLASS, $returnType);
            }
        }
        else
        {
            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetch(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result = json_encode($select->fetch(PDO::FETCH_ASSOC));
            }
            else
            {
                $select->setFetchMode(PDO::FETCH_CLASS, $returnType);
                $result = $select->fetch();
            }
        }

        if (!($select->rowCount() > 0))
        {
            return null;
        }

        return $result;
    }

    //nome da tabela, campos da tabela,condições é uma array associativo ex('id' => 33), tipo de retono, opções estrax ex (limit 10)
    public function Select($tableName, $fields = null, $conditions = null, $returnType = 'array', $advOptions = null)
    {
        $result = null;
        try
        {
            $rows = '*';
            $option = '';

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            if ($advOptions != null)
            {
                $option = $advOptions;
            }

            if ($fields != null)
            {
                $rows = $this->implodeFields($fields);
            }

            $query = 'SELECT ' . $rows . ' FROM  "' . $tableName . '" ' . $option;

            if ($conditions != null)
            {
                $query = 'SELECT ' . $rows . ' FROM  "' . $tableName . '" WHERE ' . $this->implodeConditions($conditions) . ' ' . $option;
            }
            //echo $query;
            $select = $this->connection->prepare($query);
            $select->execute();

            if ($returnType == self::$ARRAY_FORMAT)
            {
                $result = $select->fetch(PDO::FETCH_ASSOC);
            }
            else if ($returnType == self::$JSON_FORMAT)
            {
                $result = json_encode($select->fetch(PDO::FETCH_ASSOC));
            }
            else
            {
                $select->setFetchMode(PDO::FETCH_CLASS, $returnType);
                $result = $select->fetch();
            }

            if (!($select->rowCount() > 0))
            {
                return null;
            }
        }
        catch (PDOException $e)
        {
            throw new PDOException($e);
        }
        return $result;
    }

    public function Insert($tableName, $contentValues, $returnId = false, $tableIdSeq = null)
    {
        $result = '-1';
        try
        {
            /*if ($returnId == true)
            {
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result["employee_id"]; 
                $ret = $queryHandle->fetchColumn();
            }*/
            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            $query = 'INSERT INTO "' . $tableName . '" (' . $contentValues->getColumns() . ') VALUES (' . $contentValues->getValues() . ') ';
            //echo $query.'<br>';
            try
            {
                $statement = $this->connection->prepare($query);
                //$conn->beginTransaction();
                $statement->execute();
                //$conn->commit();
                if ($returnId == true)
                {
                    if ($this->SELECTED_CONNECTION_SETS['driver'] == 'pgsql')
                    {
                        if ($tableIdSeq == null)
                        {
                            $result = $this->connection->lastInsertId($tableName . '_id_seq');
                        }
                        else
                        {
                            $result = $this->connection->lastInsertId($tableIdSeq);
                        }
                    }
                    else
                    {
                        $result = $this->connection->lastInsertId();
                    }
                }
            }
            catch (PDOException $e)
            {
                //$conn->rollBack();
                throw new PDOException($e);
            }
        }
        catch (Exception  $e)
        {
            throw new Exception($e);
        }
        return $result;
    }
    //atualiza uma tabela, parametros: nome da tabela, array assoc condições, e ContentValues
    public function Update($tableName, $conditions, $contentValues)
    {
        try
        {
            $query = "UPDATE " . $tableName . ' SET  ' . $contentValues->getUpdateString() . ' WHERE ' . $this->implodeConditions($conditions);

            $conn = null;

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }


            $prepare = $this->connection->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível atualizar o registro!', 400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e);
        }
    }
    //ATUALIZA UMA TABELA A PARTIR DE UMA ISTANCIA DE CONTENTVALUES
    public function UpdateSimple(ContentValues $contentValues)
    {
        try
        {
            $query = "UPDATE " . $contentValues->getTableName() . ' SET  ' . $contentValues->getUpdateString() . ' WHERE ' . $this->implodeConditions($contentValues->getWhereConditions());

            $conn = null;

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }


            $prepare = $this->connection->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível atualizar o registro!', 400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e);
        }
    }

    public function Delete($tableName, $conditions, $cascade = false)
    {
        try
        {
            $cas = '';
            if ($cascade)
            {
                $cas = ' CASCADE ';
            }
            $query = "DELETE FROM " . $tableName . ' WHERE ' . $this->implodeConditions($conditions) . ' ' . $cas;
            $conn = null;

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            $prepare = $conn->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível deletar o registro', 400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e);
        }
    }

    public function DeleteSimple(ContentValues $contentValues)
    {
        try
        {
            $cas = '';
            if ($contentValues->getCascade())
            {
                $cas = ' CASCADE ';
            }
            $query = "DELETE FROM " . $contentValues->getTableName() . ' WHERE ' . $this->implodeConditions($contentValues->getWhereConditions()) . ' ' . $cas;
            $conn = null;

            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }

            $prepare = $conn->prepare($query);
            $prepare->execute();
            if ($prepare->rowCount() == -1)
            {
                throw new Exception('Não foi possível deletar o registro', 400);
            }
        }
        catch (PDOException  $e)
        {
            throw new Exception($e);
        }
    }

    //Verifica se uma linha existe no banco, se existir retorna o id ou a result coluna especificada, se não retorna -1
    public function CheckIfRowExist($tableName, $checkValue, $checkColumn = 'id', $resultCol = 'id')
    {
        $result = '-1';
        try
        {
            if ($this->connection == null)
            {
                throw new Exception('Conecção do banco de dados é nula! Conect ao banco antes de chamar esta função', 400);
            }
            $query = 'SELECT * FROM "' . $tableName . '" WHERE "' . $checkColumn . '"=' . "'" . $checkValue . "'";
            //echo $query.'<br>';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row)
            {
                $result = $row[$resultCol];
            }
            else
            {
                $result = '-1';
            }
        }
        catch (PDOException $e)
        {
            throw new PDOException($e);
        }
        return $result;
    }

    public function implodeConditions($arrayAsoc, $equalOrlike = '=', $sqlFunctionForLike = null, $andOr = ' AND ')
    {
        $i = 0;
        $stringTemp = '';
        $arraySize = count($arrayAsoc);
        $and = '';
        if ($arraySize >= 1)
        {
            $and = $andOr;
        }
        foreach ($arrayAsoc as $key => $value)
        {
            if ($i != ($arraySize - 1))
            {
                if ($equalOrlike == '=')
                {
                    $stringTemp .= ' "' . $key . '" = ' . "'" . $value . "' " . $and;
                }
                else if ($equalOrlike == 'like')
                {
                    if ($sqlFunctionForLike != null)
                    {
                        $stringTemp .= ' ' . $sqlFunctionForLike . '(CAST("' . $key . '" AS VARCHAR))' . ' ILIKE ' . $sqlFunctionForLike . "('%" . $value . "%')" . $and;

                    }
                    else
                    {
                        $stringTemp .= ' "' . '(CAST("' . $key . '" AS VARCHAR))' . '" ILIKE ' . "'%" . $value . "%' " . $and;
                    }
                }
                else
                {
                    $stringTemp .= ' "' . $key . '" ' . $equalOrlike . ' ' . "'" . $value . "' " . $and;
                }
            }
            else
            {
                if ($equalOrlike == '=')
                {
                    $stringTemp .= ' "' . $key . '" = ' . "'" . $value . "' ";
                }
                else if ($equalOrlike == 'like')
                {
                    if ($sqlFunctionForLike != null)
                    {
                        $stringTemp .= ' ' . $sqlFunctionForLike . '(CAST("' . $key . '" AS VARCHAR))' . ' ILIKE ' . $sqlFunctionForLike . "('%" . $value . "%')";
                    }
                    else
                    {
                        $stringTemp .= ' "' . '(CAST("' . $key . '" AS VARCHAR))' . '" ILIKE ' . "'%" . $value . "%' ";
                    }
                }
                else
                {
                    $stringTemp .= ' "' . $key . '" ' . $equalOrlike . ' ' . "'" . $value . "' ";
                }
            }
            $i++;
        }
        return $stringTemp;
    }

    public function implodeFields($array)
    {
        $i = 0;
        $stringTemp = '';
        $arraySize = count($array);
        $and = '';
        if ($arraySize >= 1)
        {
            $and = ' , ';
        }

        foreach ($array as $value)
        {
            if ($i != ($arraySize - 1))
            {
                $stringTemp .= '"' . $value . '" ' . $and;
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


