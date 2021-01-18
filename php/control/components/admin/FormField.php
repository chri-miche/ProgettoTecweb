<?php

require_once __ROOT__.'\control\components\summaries\PageFiller.php';


class FormField extends PageFiller
{
    private $data;

    public function __construct(Column $field)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/Field.xhtml'));
        $this->data = array();
        $this->data["{description}"] = $field->columnDescription();
        $this->data["{name}"] = $field->columnName();

        $attributi = "";
        if ($field->required()) {
            $attributi .= 'required="required" ';
            if ($field->value() != null) {
                $attributi .= 'readonly="readonly" ';
            }
        }
        if ($field->value() != null) {
            $attributi .= 'value="' . $field->value() . '" ';
        }
        $this->data["{attributi}"] = $attributi;
        $this->data["{error}"] = $field->error() ?? '';
    }

    public function resolveData()
    {
        return $this->data;
    }
}