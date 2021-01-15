<?php

require_once __ROOT__.'/control/components/admin/TableDefinition.php';

class TableRow extends Component
{

    private $field;

    public function __construct(Persistent $field)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/TableRow.xhtml'));
        $this->field = $field;
    }

    public function build()
    {
        $definitions = '';
        $keysForGET = '';
        foreach ($this->field->keyfields() as $keyfield) {
            $definitions .= (new TableDefinition($keyfield))->build();
            $keysForGET .= '&' . $keyfield->columnName() . '=' . $keyfield->value();
        }
        foreach ($this->field->fields() as $field) {
            $definitions .= (new TableDefinition($field))->build();
        }

        $HTML = str_replace("<tableDefinitions />", $definitions, $this->baseLayout());


        $HTML = str_replace("{keys}", $keysForGET, $HTML);
        return str_replace("{table}", $this->field->table(), $HTML);
    }
}