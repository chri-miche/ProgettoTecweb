<?php

require_once __ROOT__.'/control/components/admin/TableHeader.php';
require_once __ROOT__.'/control/components/admin/TableRow.php';
require_once __ROOT__.'/model/meta/Persistent.php';


class AdminTable extends Component
{
    private $results;
    private $persistent;
    private $tablename;

    public function __construct($manage, $tablename)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/Table.xhtml'));

        $this->persistent = new Persistent($manage);

        $this->tablename = $tablename;

        $this->results = $this->persistent->listFromProto();
    }

    public function build()
    {


        $headers = "";

        foreach ($this->persistent->keyfields() as $keyfield) {
            $headers .= (new TableHeader($keyfield))->build();
        }
        foreach ($this->persistent->fields() as $field) {
            $headers .= (new TableHeader($field))->build();
        }

        $rows = "";
        foreach ($this->results as $result) {
            $rows .= (new TableRow($result))->build();
        }

        $HTML = str_replace("<tableHeaders />", $headers, $this->baseLayout());
        $HTML = str_replace("<tableRows />", $rows, $HTML);

        $HTML = str_replace("{title}", $this->tablename, $HTML);

        return $HTML;
    }
}