<?php

namespace App;
class DbView
{

    protected $viewName;
    protected $dataColumns = array();
    protected $dataValues = array();
    protected $sqlCondition;
    protected $dbh = null;

    public function __construct($viewName = null, $dataColumns = null, $dataValues = null)
    {
        if (is_null($this->dbh)) {
            $this->dbh = \App\DB::connect();
        }

        if (!is_null($viewName) && !is_null($dataColumns) && !is_null($dataValues)) {
            $this->viewName = $viewName;
            $this->dataColumns = $dataColumns;
            $this->dataValues = $dataValues;
            $this->prepareSql();
        }
    }

    /**
     * @return null
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * @param null $viewName
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;
    }

    /**
     * @return array
     */
    public function getDataColumns()
    {
        return $this->dataColumns;
    }

    /**
     * @param array $dataColumns
     */
    public function setDataColumns($dataColumns)
    {
        $this->dataColumns = $dataColumns;
    }

    /**
     * @return array
     */
    public function getDataValues()
    {
        return $this->dataValues;
    }

    /**
     * @param array $dataValues
     */
    public function setDataValues($dataValues)
    {
        $this->dataValues = $dataValues;
    }

    /**
     * @return mixed
     */
    public function getSqlCondition()
    {
        return $this->sqlCondition;
    }

    /**
     * @param mixed $sqlCondition
     */
    public function setSqlCondition($sqlCondition)
    {
        $this->sqlCondition = $sqlCondition;
    }

    public function prepareSql()
    {

        $sql = ' WHERE ';

        if (!isArrayEmpty($this->dataColumns)) {
            $sql .= implode(' = ? AND ', $this->dataColumns);
        }
        $this->sqlCondition = $sql . ' = ?';
    }

    /**
     * @return bool|array
     */
    public function get()
    {

        $sql = 'SELECT * FROM ' . $this->viewName . ' ' . $this->sqlCondition;

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($this->dataValues);

        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count == 1) {
                return $stmt->fetch(\PDO::FETCH_OBJ);
            } else {
                return false;
            }
        }
    }

    /**
     * Clean class attributs
     */
    public function clean()
    {
        foreach (get_object_vars($this) as $attribut => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

            if (is_callable(array($this, $method))) {
                $this->$method('');
            }
        }
    }
}