<?php

require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "summaries" . DIRECTORY_SEPARATOR . "PageFiller.php";


class FormField extends PageFiller
{
    private $data;

    public function __construct(Column $field, $showErrors = false)
    {
        parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "Field.xhtml"));
        $this->data = array();
        $this->data["{description}"] = $field->columnDescription();
        $this->data["{name}"] = $field->columnName();

        $attributi = "";
        if ($field->isKey()) {
            if ($field->value() != null) {
                $attributi .= 'readonly="readonly" ';
            }
        }

        if ($field->required()) {
            $attributi .= 'required="required" ';

        }
        if ($field->value() != null) {
            $attributi .= 'value="' . $field->value() . '" ';
        }
        $this->data["{attributi}"] = $attributi;
        $this->data["{error}"] = $showErrors ? ($field->error() ?? '') : '';
    }

    public function resolveData()
    {
        return $this->data;
    }
}