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
        if (!empty($newval)) {
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
                default:
                    $newval = addslashes($newval);
            }
        } else {
            $newval = null;
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
}