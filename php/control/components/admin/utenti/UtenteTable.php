<?php

require_once 'UtenteRow.php';

class UtenteTable extends Component
{
    private $users;

    public function __construct()
    {
        parent::__construct(file_get_contents(__ROOT__
            . DIRECTORY_SEPARATOR . "view"
            . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "admin"
            . DIRECTORY_SEPARATOR . "utente"
            . DIRECTORY_SEPARATOR . "utentetable.xhtml"));
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