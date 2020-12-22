<?php
    /** Same impagination as login*/
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__.'\control\components\Login.php';
    require_once __ROOT__.'\control\components\Register.php';

$basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $user = new SessionUser();

    (isset($_POST['username']) && ($_POST['password'] == $_POST['password2']))?
    $page->addComponent(new Register($_POST['username'], $_POST['password'],$_POST['email'])) : $page->addComponent(new Register());

    echo $page;
?>