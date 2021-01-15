<?php

class TableDefinition extends Component
{
    private $field;

    public function __construct(Column $field) {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/TableDefinition.xhtml'));
        $this->field = $field;
    }

    public function build() {
        return str_replace("{columnDefinition}", $this->field->value(), $this->baseLayout());
    }
}