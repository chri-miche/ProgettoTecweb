<?php


class VoComponent extends Component
{
    /**
     * @var VO
     */
    private $vo;

    /*file_get_contents(__ROOT__ .
            DIRECTORY_SEPARATOR . 'view' .
            DIRECTORY_SEPARATOR . 'admin' .
            DIRECTORY_SEPARATOR . 'specie' .
            DIRECTORY_SEPARATOR . 'specieconfirmdelete.xhtml')*/
    public function __construct($html, VO $vo)
    {
        parent::__construct($html);
        $this->vo = $vo;
    }

    public function resolveData()
    {
        $values = [];
        foreach ($this->vo->arrayDump() as $key => $value) {
            $values['{' . $key . '}'] = $value;
        }
        return $values;
    }

}