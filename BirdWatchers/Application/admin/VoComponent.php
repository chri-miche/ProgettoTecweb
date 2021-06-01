<?php


class VoComponent extends Component {
    /*** @var VO */
    private $vo;

    public function __construct($html, VO $vo) {
        parent::__construct($html);
        $this->vo = $vo;
    }

    public function resolveData() {
        $values = [];
        foreach ($this->vo->arrayDump() as $key => $value) {
            $values['{' . $key . '}'] = $value;
        }
        return $values;
    }

}