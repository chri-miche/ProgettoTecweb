<?php



class Column
{

    private $rawColumn;

    private $columnDescription;

    private $columnType;

    private $value;

    private $default;

    private $error;

    public function __construct($rawColumn)
    {
        $this->rawColumn = $rawColumn;
        $this->value = null;
        if ($this->value === 'NULL') $this->value = null;
        $this->default = $rawColumn['COLUMN_DEFAULT']; // che può anche esser null
        if ($this->default === 'NULL') $this->value = null;
        $this->columnDescription = $this->rawColumn['COLUMN_COMMENT'];
    }

    public function columnDescription() {
        if (!isset($this->columnDescription) || empty($this->columnDescription)) {
            $this->columnDescription = $this->columnName();
        }
        return $this->columnDescription;
    }

    public function columnType() {
        if (!isset($this->columnType)) {
            switch ($this->rawColumn['DATA_TYPE']) {
                case 'int':
                case 'tinyint':
                    $this->columnType = 'number';
                    break;
                case 'date':
                    $this->columnType = 'date';
                    break;
                case 'text':
                default:
                    $this->columnType = 'text';
            }
        }
        return $this->columnType;
    }

    public function required() {
        return $this->rawColumn['IS_NULLABLE'] === 'NO';
    }

    public function setValue($newval) {
        if (isset($newval)) {
            switch ($this->columnType()) {
                case 'number':
                    if (!is_numeric($newval)) {
                        $this->error = "In questo campo &egrave; richiesto un numero.";
                        return false;
                    }
                    switch ($this->rawColumn['DATA_TYPE']) {
                        case 'int':
                        case 'tinyint':
                            $newval = intval($newval);
                            break;
                        default:
                            $newval = floatval($newval);
                    }
                    break;
                case 'date':
                    break;
                case 'text':
                default:
                    $newval = addslashes($newval);
            }
        }
        if (!isset($newval) && $this->required()) {
            $this->error = "Il campo è richiesto.";
            return false;
        }
        $this->value = $newval;
        unset($this->error);
        return true;
    }

    public function value() {
        return $this->value;
    }

    public function defaultValue() {
        return $this->default;
    }

    public function columnName() {
        return $this->rawColumn['COLUMN_NAME'];
    }

    public function hasError() {
        return isset($this->error);
    }

    public function error() {
        if ($this->hasError()) {
            return $this->error;
        } else {
            return null;
        }
    }

    public function tableName()
    {
        return $this->rawColumn['TABLE_NAME'];
    }

    public function getSqlValue() {
        if ($this->value() === null) {
            return 'NULL';
        } else {
            switch ($this->columnType()) {
                case 'number':
                    return $this->value();
                default:
                    return "'" . $this->value() . "'";
            }
        }
    }

    public function tryFixInsertValue() {
        if (!isset($this->value)) {
            switch ($this->columnType()) {
                case 'number':
                    // prob un id
                    $result = DatabaseAccess::executeQuery("select max(" . $this->columnName() . ") as massimo from " . $this->tableName() . ";")[0]['massimo'];
                    $this->setValue($result + 1);
                    break;
                default:
                    // do nothing
            }
        }
    }
}