<?php

require_once __ROOT__.'/control/components/admin/FormField.php';

class AdminForm extends Component
{

    private $persistent;
    private $action;

    public function __construct($manage, $keys = array(), $defaultPersistent = null)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/Form.xhtml'));

        $this->action = count($keys) === 0 ? "create" : "update";

        if (isset($defaultPersistent)) {
            $this->persistent = $defaultPersistent;
        } else {
            ($this->persistent = new Persistent($manage))->setDefaultValues();

            if (count($keys) > 0) {
                foreach ($keys as $column => $value) {
                    $this->persistent->set($column, $value);
                }
                $this->persistent = $this->persistent->getUniqueFromProto();
            }
        }
    }

    public function build()
    {
        $HTML = $this->baseLayout();

        $keyFields = '';
        foreach ($this->persistent->keyfields() as $keyfield) {
            $keyFields .= (new FormField($keyfield))->build();
        }

        $HTML = str_replace("<keyFields />", $keyFields, $HTML);

        $fields = '';
        foreach ($this->persistent->fields() as $field) {
            $fields .= (new FormField($field))->build();
        }

        $HTML = str_replace("<fields />", $fields, $HTML);

        // TODO read update

        $HTML = str_replace("{table}", $this->persistent->tableName(), $HTML);

        return str_replace("{action}", $this->action, $HTML);
    }
}