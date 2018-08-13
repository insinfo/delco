<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 14/07/2017
 * Time: 22:07
 */

namespace Delco\Util;


class ContentValues
{
    private $values = array();
    private $query;
    private $tableName;
    private $conditions;
    private $cascade = false;

    /**
     * @return mixed
     */
    public function getCascade()
    {
        return $this->cascade;
    }

    /**
     * @param mixed $cascade
     */
    public function setCascade($cascade)
    {
        $this->cascade = $cascade;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setWhereConditions($arrayAssocConditions)
    {
        $this->conditions = $arrayAssocConditions;
    }

    public function getWhereConditions()
    {
        return $this->conditions;
    }

    public function put($key, $value)
    {
        $string = null;
        if ($value == '')
        {
            $string = 'null';
        }
        else if ($value == null)
        {
            $string = 'null';
        }
        else
        {
            $string = "'" . $value . "'";
        }
        $this->values += [$key => $string];
    }

    public function getColumns()
    {
        $keys = array_keys($this->values);
        $columns = array();
        foreach ($keys as $val)
        {
            $columns[] = '"' . $val . '"';
        }
        return implode(", ", $columns);
    }

    public function getValues()
    {
        return implode(", ", $this->values);
    }

    public function getUpdateString()
    {
        $result = "";
        $keys = array_keys($this->values);
        $i = 0;
        foreach ($this->values as $val)
        {
            if ($i != (count($this->values) - 1))
            {
                $result .= ' "' . $keys[$i] . '" ' . "=" . $val . ",";
            }
            else
            {
                $result .= ' "' . $keys[$i] . '" ' . "=" . $val . ' ';
            }
            $i++;
        }
        return $result;
    }

    public function addQuery($query)
    {
        $this->query = $query;
    }

    public function bindTable($search, $replace)
    {
        $this->query = str_replace($search, $replace, $this->query);
    }

    public function bindColumn($search, $replace)
    {
        $this->query = str_replace($search, $replace, $this->query);
    }

    public function bindValue($search, $replace)
    {
        $this->query = str_replace($search, "'" . $replace . "'", $this->query);
    }

    public function getQuery()
    {
        return $this->query;
    }


}