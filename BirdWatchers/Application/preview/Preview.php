<?php

require_once __DIR__ . "/../databaseObjects/VO.php";
require_once __DIR__ . "/../Component.php";

/** Preview generica, il fill è relativo alle chiavi del oggetto virtuale dato in costruzione.*/
class Preview extends Component {

    private $data;

    public function __construct(VO $data, string $HTML) {
        parent::__construct($HTML);
        $this->data = $data;
    }


    public function resolveData() {

        $resolvedData = [];

        foreach ($this->data->arrayDump() as $key => $value)
            $resolvedData['{' . $key . '}'] = $value;

        return $resolvedData;
    }
}