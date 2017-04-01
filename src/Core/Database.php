<?php
namespace Core;

use PDO;
use PDOException;

class Database extends PDO
{
    private static $instance;
    private static $type;
    private static $name;
    private static $host;
    private static $username;
    private static $password;
    private static $port;

    public function execute($sql, $params = array())
    {
        try {
            $statement = $this->prepare($sql);
            $statement->execute($params);
            if (preg_match('/insert/i', $sql)) {
                return $this->lastInsertId();
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

        }
    }

    public function selectOne($table, $fields = array("*"), $where = array())
    {
        $fields = implode(', ', array_values($fields));
        $sql = "SELECT $fields FROM $table";
        if (!empty($where)) {
            $sql .=  " WHERE ".$this->details($where);
        }
        try {
            $statement = $this->prepare($sql);
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

        }
    }

    public function selectAll($table, $fields = array("*"), $where = array())
    {
        $fields = implode(', ', array_values($fields));
        $sql = "SELECT $fields FROM $table";
        if (!empty($where)) {
            $sql .=  " WHERE " . $this->details($where);
        }
        try {
            $statement = $this->prepare($sql);
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

        }
    }

    public function insert($table, $data)
    {
        ksort($data);
        $names = implode(', ', array_keys($data));
        $values = ':data_'.implode(', :data_', array_keys($data));
        try {
            $statement = $this->prepare("INSERT INTO $table ($names) VALUES ($values)");
            $statement = $this->binds($statement, $data);
            $statement->execute();
            return $this->lastInsertId();
        } catch (PDOException $e) {

        }
    }

    public function update($table, $data, $where)
    {
        ksort($data);
        ksort($where);
        $dataDetails = $this->details($data, ',');
        $whereDetails = $this->details($where, 'AND', 'where');
        try {
            $statement = $this->prepare("UPDATE $table SET $dataDetails WHERE $whereDetails");
            $statement = $this->binds($statement, $data);
            $statement = $this->binds($statement, $where, 'where');
            $statement->execute();
            return $statement->rowCount();
        } catch (PDOException $e) {

        }
    }

    public function delete($table, $where)
    {
        ksort($where);
        $whereDetails = $this->details($where);
        try {
            $statement = $this->prepare("DELETE FROM $table WHERE $whereDetails");
            $statement = $this->binds($statement, $where);
            $statement->execute();
            return $statement->rowCount();
        } catch (PDOException $e) {

        }
    }

    private function details($data, $separator = 'AND', $identifier = 'data')
    {
        $details = '';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i === 0) {
                $details .= $key.'=:'.$identifier.'_'.$key;
            } else {
                $details .= ' '.$separator.' '.$key.'=:'.$identifier.'_'.$key;
            }
            $i++;
        }
        return ltrim($details, " $separator ");
    }

    private function binds($statement, $data, $identifier = 'data')
    {
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $statement->bindValue(":".$identifier."_".$key, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $statement->bindValue(":".$identifier."_".$key, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $statement->bindValue(":".$identifier."_".$key, $value, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(":".$identifier."_".$key, $value, PDO::PARAM_STR);
            }
        }
        return $statement;
    }

    public static function employ($type, $name, $host, $username, $password, $port)
    {
        self::$type = $type;
        self::$name = $name;
        self::$host = $host;
        self::$username = $username;
        self::$password = $password;
        self::$port = (int) $port;
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self(self::$type.':host='.self::$host.';dbname='.self::$name.';port='.self::$port, self::$username, self::$password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        return self::$instance;
    }
}
