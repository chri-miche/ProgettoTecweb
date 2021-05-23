<?php

class VoTable extends Component
{
    /*file_get_contents()*/
    /**
     * @var string
     */
    private $rowHtml;
    /**
     * @var DAO
     */
    private $dao;

    public function __construct(array $htmls, DAO $dao)
    {
        parent::__construct($htmls['table']);
        $this->rowHtml = $htmls['row'];
        $this->dao = $dao;
    }

    public function build()
    {
        $vos = $this->dao->getAll();
        $content = '';
        foreach ($vos as $vo) {
            $content .= (new VoComponent($this->rowHtml, $vo))->build();
        }
        return str_replace('<table-content />', $content, parent::build());
    }
}