<?php

class SpecieTable extends Component
{
    public function __construct()
    {
        parent::__construct(file_get_contents(__ROOT__ .
            DIRECTORY_SEPARATOR . 'view' .
            DIRECTORY_SEPARATOR . 'admin' .
            DIRECTORY_SEPARATOR . 'specie' .
            DIRECTORY_SEPARATOR . 'specietable.xhtml'));
    }

    public function build()
    {
        $dao = new SpecieDAO();
        $vos = $dao->getAll();
        $content = '';
        foreach ($vos as $vo) {
            $content .= (new SpecieRow($vo))->build();
        }
        return str_replace('<table-content />', $content, parent::build());
    }
}