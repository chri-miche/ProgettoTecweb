<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__.'\control\components\Login.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    (isset($_POST['username']) && isset($_POST['password']))?
        $page->addComponent(new Login($_POST['username'], $_POST['password'])) : $page->addComponent(new Login());

    echo $page;

?>