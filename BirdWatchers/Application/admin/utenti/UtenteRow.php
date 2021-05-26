<?php


class UtenteRow extends Component
{

    private $userVO;

    private $properties = ['id', 'admin', 'nome', 'email', 'immagine'];

    public function __construct(UserVO $userVO)
    {
        parent::__construct(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "utenterow.xhtml"));
        $this->userVO = $userVO;
    }

    public function build()
    {
        $html = parent::build();
        foreach ($this->properties as $property) {
            if ($property === 'admin') {
                $html = str_replace('{admin}', $this->userVO->$property == '1' ? 'Sì' : 'No', $html);
            } else {
                $html = str_replace('{' . $property . '}', $this->userVO->$property, $html);
            }
        }
        return $html;
    }
}