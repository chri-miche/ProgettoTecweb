<?php

require_once 'UtentiRow.php';

class UtentiAdmin extends Component
{


    private $users;

    public function __construct()
    {
        parent::__construct(file_get_contents(__ROOT__."/control/components/admin/utenti/utentitable.xhtml"));
        $this->users = (new UserDAO())->getAll();
    }

    public function build()
    {
        $html = parent::build();

        $rows = '';

        foreach ($this->users as $user) {
            $rows .= (new UtentiRow($user))->build();
        }

        return str_replace('<tableContent />', $rows, $html);
    }
}