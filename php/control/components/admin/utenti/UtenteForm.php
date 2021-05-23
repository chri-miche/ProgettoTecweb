<?php


class UtenteForm extends Component
{
    /**
     * @var UserVO
     */
    private $user;

    public function __construct(UserVO $user)
    {
        parent::__construct(file_get_contents(__ROOT__
            . DIRECTORY_SEPARATOR . "view"
            . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "admin"
            . DIRECTORY_SEPARATOR . "utente"
            . DIRECTORY_SEPARATOR . "utenteform.xhtml"));
        $this->user = $user;
    }

    public function resolveData()
    {
        $data = [];
        foreach($this->user->arrayDump() as $key => $value) {
            $data['{' . $key . '}'] = $value;
        }
        $data['{nome_error}'] = "";
        $data['{is_admin_error}'] = "";
        $data['{selected_no}'] = !$this->user->isAdmin() ? 'selected="selected"' : '';
        $data['{selected_yes}'] = $this->user->isAdmin() ? 'selected="selected"' : '';
        return $data;
    }

}
?>