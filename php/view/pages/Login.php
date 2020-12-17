<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__.'\control\components\Login.php';


    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    if(!$page->addComponent(new Login()))
        echo 'Ooops something went wrong';

    echo $page;

?>