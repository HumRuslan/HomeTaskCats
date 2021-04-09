<?php
namespace core;

use \PDO;

abstract class BaseModel
{
    static $table = 'table';
    static $sql_str = '';

    abstract public function rules();

    public function loadPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $data = $_POST;
            $fields = get_object_vars($this);
            foreach ($fields as $key => $value) {
                if (isset($data[$key])) {
                    $this->{$key} = $data[$key];
                }
            }
            return true;
        }
        return false;
    }

    public function loadGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $data = $_POST;
            $fields = get_object_vars($this);
            foreach ($fields as $key => $value) {
                if (isset($data[$key])) {
                    $this->{$key} = $data[$key];
                }
            }
            return true;
        }
        return false;
    }

    public function validate()
    {
        $error = false;
        $error_message = '';
        foreach ($this->rules() as $key => $fields) {
            switch ($key) {
                case 'required':
                    foreach ($fields as $field) {
                        if ($this->{$field} == '') {
                            $error_message .= $field . ' is required. <br>';
                            $error = true; 
                        }
                    }
                break;

                // Зробити перевірку по типу данних
            }
        }
        if (isset($_SESSION)) session_start();
        if ($error) $_SESSION['error'] = $error_message;
        return !$error;
    }

    public function save()
    {
        $fields = get_object_vars($this);
        $keys = [];
        $values = [];
        foreach ($fields as $key => $value) {
            if ($value || is_bool($value)) {
                $keys[] = $key;
                $values[] = ":$key";
            }
        }
        $conn = ConnectDB::connectDB();
        $table = static::$table;
        $sql_keys = implode(', ', $keys);
        $sql_values = implode(', ', $values);
        $stmt = $conn->prepare("INSERT INTO $table ($sql_keys) VALUES ($sql_values)");
        foreach ($fields as $key => $value) {
            if ($value || is_bool($value)) {
                $stmt->bindParam(":$key", $fields[$key]);
            }
        }
        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId();
            return $this;
        }
        return false;
    }

    // 1. find SELECT * FROM table
            // 2. where - add field and value
            // 3. All - виконати наш запит и повернути значення (массив об'їєктів)
            // 4. One - виконати і повернути об'єкт
            // ['field' => 'value']

    static public function find()
    {
        $obj_name = get_called_class();
        $obj = new $obj_name;
        $table = static::$table;
        static::$sql_str = "SELECT * FROM `$table`";
        return $obj;
    }

    public function where($params = [])
    {
        if ($params) {
            $sql = [];
            foreach ($params as $key => $value) {
                $value = htmlspecialchars($value);
                $sql[] = "`$key` = '$value'";
            }
            static::$sql_str .= " WHERE " . implode(' AND ', $sql);
        }
        return $this;
    }
    
    public function one()
    {
        $conn = ConnectDB::connectDB();
        $stmt = $conn->prepare(static::$sql_str);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function all()
    {
        $conn = ConnectDB::connectDB();
        $stmt = $conn->prepare(static::$sql_str);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    static function delete($params = [])
    {
        $conn = ConnectDB::connectDB();
        $table = static::$table;
        $sql_where = [];
        foreach ($params as $key => $value) {
            $sql_where[] = "`$key`='$value'";
        }
        $sql_where = implode(' AND ', $sql_where);
        $sql = "DELETE FROM $table WHERE $sql_where";
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public function update($params = [])
    {
        $conn = ConnectDB::connectDB();
        $table = static::$table;
        $data = get_object_vars($this);
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = "`$key` = :$key";
        }
        $sql_where = [];
        foreach ($params as $key => $value) {
            $sql_where[] = "`$key` = '$value'";
        }
        $sql_where = implode(' AND ', $sql_where);
        $sql_set = implode(', ', $values);
        $sql = "UPDATE `$table` SET $sql_set WHERE $sql_where";
        $stmt = $conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindParam(":$key", $data[$key]);
        }
        return $stmt->execute();
    }
}