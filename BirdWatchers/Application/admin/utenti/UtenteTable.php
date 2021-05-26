<?php

require_once 'UtenteRow.php';

class UtenteTable extends Component
{
    private $users;

    public function __construct()
    {
        parent::__construct(file_get_contents("utentetable.xhtml"));
        $this->users = (new UserDAO())->getAll();
    }

    public function build()
    {
        $html = parent::build();

        $rows = '';

        foreach ($this->users as $user) {
            $rows .= (new UtenteRow($user))->build();
        }

        return str_replace('<table-content />', $rows, $html);
    }
}