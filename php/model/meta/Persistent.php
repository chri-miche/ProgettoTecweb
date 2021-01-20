<?php

require_once "Column.php";

class Persistent {

    private $table; // sanitized table
    private $tableName;
    private $tableType;
    // [columnName => Column]
    private $columns;

    // [columnName => Column]
    private $keys;

    public function __construct(string $table, array $prototype = array())
    {
        $this->table = trim(htmlspecialchars($table));

        $this->loadMetadata();

        foreach ($prototype as $key => $value) {
            $this->set($key, $value);
        }
    }

    private function loadMetadata() {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $db = 'information_schema';

        $connection = mysqli_connect(
            $host,
            $user,
            $password,
            $db
        );

        if (mysqli_connect_errno() != 0) throw new Exception(mysqli_connect_error());

        $tableInfo =
            mysqli_fetch_assoc(
                mysqli_query($connection, "select * from TABLES where TABLE_SCHEMA = 'webbirddb' and TABLE_NAME = '$this->table';")
            );

        $this->tableType = $tableInfo['TABLE_TYPE'];
        $this->tableName = $tableInfo['TABLE_COMMENT'] ?? $this->table;

        $columns =
            mysqli_query($connection,
        "select * from `COLUMNS` where TABLE_SCHEMA = 'webbirddb' and TABLE_NAME = '$this->table';");

        foreach ($columns as $column) {
            if ($column['COLUMN_KEY'] === 'PRI') {
                $this->keys[$column['COLUMN_NAME']] = new Column($column);
            } else {
                $this->columns[$column['COLUMN_NAME']] = new Column($column);
            }
        }

        mysqli_close($connection);
    }

    public function set($key, $value) {
        $reason = ''; // string | true
        if (isset($this->keys[$key])) {
            $reason = $this->keys[$key]->setValue($value);
        }
        if (isset($this->columns[$key])) {
            $reason = $this->columns[$key]->setValue($value);
        }
        return $reason;
    }

    public function keyfields() {
        return $this->keys;
    }

    public function fields() {
        return $this->columns;
    }

    public function tableName() {
        return $this->table;
    }

    public function listFromProto() {
        $query = "select * from $this->table where ";

        foreach ($this->keys as $name => $key) {
            $value = $key->value();
            if (!empty($value)) $query .= " $name = '" . $value . "' and";
        }
        foreach ($this->columns as $name => $key) {
            $value = $key->value();
            if (!empty($value)) $query .= " $name = '" . $value . "' and";
        }

        $query .= " true ;";

        return
            array_map(
                function ($obj) {
                    return new Persistent($this->table, $obj);
                    },
                DatabaseAccess::executeQuery($query)
            );
    }

    public function table() {
        return $this->table;
    }

    public function getUniqueFromProto() {
        $list = $this->listFromProto();
        $count = count($list);
        if ($count === 0) {
            throw new Exception("Elemento del database non trovato.");
        } else if ($count > 1) {
            throw new Exception("Trovati pi&ugrave; elementi con la stessa chiave primaria.");
        } else {
            return $list[0];
        }
    }

    // option: update | create
    public function commitFromProto($option = "create") {

        foreach ($this->keys as $name => $column) {

            if ($column->hasError()) {
                return false;
            }
        }

        foreach ($this->columns as $column) {
            if ($column->hasError()) {
                return false;
            }
        }

        $sql = '';
        if ($option === "create") {
            $columns = '(';
            $values = '(';
            foreach ($this->keys as $key => $value) {
                $quotes = $value->columnType() === "text"  && $value->value() !== null ? "'" : "";
                $columns .= "$key,";
                $values .= $quotes . ($value->value() ?? "NULL") . $quotes . ",";
            }
            foreach ($this->columns as $key => $value) {
                $quotes = $value->columnType() === "text"  && $value->value() !== null ? "'" : "";
                $columns .= "$key,";
                $values .= $quotes . ($value->value() ?? "NULL") . $quotes . ",";
            }
            $columns = preg_replace('/,$/', ')', $columns);
            $values = preg_replace('/,$/', ')', $values);
            $sql = "insert into $this->table $columns values $values;";
        } else {
            $values = '';
            foreach ($this->columns as $key => $value) {
                $quotes = $value->columnType() === "text"  && $value->value() !== null ? "'" : "";
                $values .= "$key = " . $quotes . ($value->value() ?? "NULL") . $quotes . ",";
            }
            $values = preg_replace('/,$/', '', $values);

            $keys = Persistent::buildWhereStatement($this->keys);

            $sql = "update $this->table set $values where $keys;";
        }
        try {
            DatabaseAccess::writeRecord($sql);
            return true;
        } catch (Exception $err) {
            return false;
        }
    }

    public function deleteFromProto()
    {

        $keys = Persistent::buildWhereStatement($this->keys);

        $sql = "delete from $this->table where $keys;";

        try {
            DatabaseAccess::deleteRecord($sql);
            return true;
        } catch (Exception $err) {
            return false;
        }
    }

    private static function buildWhereStatement($array) {
        $statement = '';
        foreach ($array as $key => $value) {
            $quotes = $value->columnType() === "text" && $value->value() !== null ? "'" : "";
            $statement .= " $key = " . $quotes . ($value->value() ?? "NULL") .$quotes .  " and";
        }
        return preg_replace('/and$/', '', $statement);
    }

}