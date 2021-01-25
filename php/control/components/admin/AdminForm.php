<?php

require_once 'FormField.php';

class AdminForm extends Component
{

    private $persistent;
    private $action;
    /**
     * @var bool
     */
    private $showErrors = false;

    public function __construct($manage, $keys = array(), $defaultPersistent = null)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/Form.xhtml'));

        $this->action = count($keys) === 0 ? "create" : "update";

        if (isset($defaultPersistent)) {
            $this->showErrors = true;
            $this->persistent = $defaultPersistent;
        } else {
            $this->persistent = new Persistent($manage);

            if (count($keys) > 0) {
                foreach ($keys as $column => $value) {
                    $this->persistent->set($column, $value);
                }
                $this->persistent = $this->persistent->getUniqueFromProto();
            } else {
                $this->persistent->setDefaultValues();
            }
        }
    }

    public function build()
    {
        $HTML = $this->baseLayout();

        $keyFields = '';
        foreach ($this->persistent->keyfields() as $keyfield) {
            $keyFields .= (new FormField($keyfield, $this->showErrors))->build();
        }

        $HTML = str_replace("<keyFields />", $keyFields, $HTML);

        $fields = '';
        foreach ($this->persistent->fields() as $field) {
            $fields .= (new FormField($field, $this->showErrors))->build();
        }

        $HTML = str_replace("<fields />", $fields, $HTML);

        // TODO read update

        $HTML = str_replace("{table}", $this->persistent->tableName(), $HTML);

        return str_replace("{action}", $this->action, $HTML);
    }
}