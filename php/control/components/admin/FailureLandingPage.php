<?php


class FailureLandingPage extends LandingPage
{
    public function __construct($manage)
    {
        parent::__construct($manage, 'Operazione fallita, si prega di riprovare.', 'failure');
    }
}