<?php


class ConfirmDeleteUtente extends Component
{
    /**
     * @var UserVO
     */
    private $vo;

    public function __construct(UserVO $vo)
    {
        parent::__construct(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "confirmdeleteutente.xhtml"));
        $this->vo = $vo;
    }

    public function resolveData()
    {
        $data = [];
        foreach ($this->vo->arrayDump() as $key => $value) {
            if ($key === 'admin') {
                $data['{admin}'] = $value === '1' ? 'Sì' : 'No';
            } else {
                $data['{' . $key . '}'] = $value;
            }
        }
        return $data;
    }
}