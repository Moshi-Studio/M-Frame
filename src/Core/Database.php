<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private $instance;

    public function __construct($type, $host, $name, $port, $username, $password)
    {
        try {
            $this->instance = new PDO(
                "{$type}:host={$host};dbname={$name};port={$port}",
                $username, $password,
                array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function execute($sql, $params = array())
    {
        try {
            $statement = $this->instance->prepare($sql);
            $statement->execute($params);
            if (preg_match('/insert/i', $sql)) {
                return $this->instance->lastInsertId();
            } elseif (preg_match('/update/i', $sql)) {
                return $statement->rowCount();
            } elseif (preg_match('/delete/i', $sql)) {
                return $statement->rowCount();
            } elseif (preg_match('/select/i', $sql)) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return $statement->rowCount();
            }
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function selectOne($table, $fields = array('*'), $where = array())
    {
        $fields = implode(', ', array_values($fields));
        $sql = "SELECT {$fields} FROM {$table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . $this->details($where);
        }
        try {
            $statement = $this->instance->prepare($sql);
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function selectAll($table, $fields = array('*'), $where = array())
    {
        $fields = implode(', ', array_values($fields));
        $sql = "SELECT {$fields} FROM {$table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . $this->details($where);
        }
        try {
            $statement = $this->instance->prepare($sql);
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function insert($table, $data)
    {
        ksort($data);
        $names = implode(', ', array_keys($data));
        $values = ':data_' . implode(', :data_', array_keys($data));
        try {
            $statement = $this->instance->prepare("INSERT INTO {$table} ({$names}) VALUES ({$values})");
            $statement = $this->binds($statement, $data);
            $statement->execute();
            return $this->instance->lastInsertId();
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function update($table, $data, $where)
    {
        ksort($data);
        ksort($where);
        $dataDetails = $this->details($data, ',');
        $whereDetails = $this->details($where, 'AND', 'where');
        try {
            $statement = $this->instance->prepare("UPDATE {$table} SET {$dataDetails} WHERE {$whereDetails}");
            $statement = $this->binds($statement, $data);
            $statement = $this->binds($statement, $where, 'where');
            $statement->execute();
            return $statement->rowCount();
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    public function delete($table, $where)
    {
        ksort($where);
        $whereDetails = $this->details($where);
        try {
            $statement = $this->instance->prepare("DELETE FROM {$table} WHERE {$whereDetails}");
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->rowCount();
        } catch (PDOException $e) {
            mException()::raise($e->getMessage());
        }
    }

    private function details($data, $separator = 'AND', $identifier = 'data')
    {
        $details = '';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i === 0) {
                $details .= $key . '=:' . $identifier . '_' . $key;
            } else {
                $details .= ' ' . $separator . ' ' . $key . '=:' . $identifier . '_' . $key;
            }
            $i++;
        }
        return ltrim($details, " {$separator} ");
    }

    private function binds($statement, $data, $identifier = 'data')
    {
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $statement->bindValue(":" . $identifier . "_" . $key, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $statement->bindValue(":" . $identifier . "_" . $key, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $statement->bindValue(":" . $identifier . "_" . $key, $value, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(":" . $identifier . "_" . $key, $value, PDO::PARAM_STR);
            }
        }
        return $statement;
    }
}
