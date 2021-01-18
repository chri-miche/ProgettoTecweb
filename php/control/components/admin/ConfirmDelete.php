<?php


class ConfirmDelete extends Component
{
    private $persistent;

    public function __construct($manage, $keys)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/ConfirmDelete.xhtml'));
        $this->persistent = new Persistent($manage, $keys);
        $this->persistent = $this->persistent->getUniqueFromProto();
    }

    public function build()
    {
        $headers = "";
        $row = "";
        $keysForGET = '';

        foreach ($this->persistent->keyfields() as $keyfield) {
            $headers .= (new TableHeader($keyfield))->build();
            $row .= (new TableDefinition($keyfield))->build();
            $keysForGET .= '&' . $keyfield->columnName() . '=' . $keyfield->value();
        }
        foreach ($this->persistent->fields() as $field) {
            $headers .= (new TableHeader($field))->build();
            $row .= (new TableDefinition($field))->build();
        }

        $HTML = str_replace("<tableHeader />", $headers, $this->baseLayout());
        $HTML = str_replace("<tableRow />", $row, $HTML);

        $HTML = str_replace("{table}", $this->persistent->tableName(), $HTML);
        $HTML = str_replace("{manage}", $this->persistent->table(), $HTML);
        $HTML = str_replace("{keys}", $keysForGET, $HTML);

        return $HTML;
    }
}