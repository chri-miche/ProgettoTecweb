<?php


class LandingPage extends Component {
    private $manage;
    private $message;
    private $result;

    public function __construct($manage, $message, $result)
    {
        parent::__construct(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'LandingPage.xhtml'));
        $this->manage = $manage;
        $this->message = $message;
        $this->result = $result;
    }

    public function resolveData()
    {
        return array(
            "{message}" => $this->message,
            "{result}" => $this->result,
            "{manage}" => $this->manage
        );
    }
}
?>