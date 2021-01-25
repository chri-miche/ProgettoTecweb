<?php
    /** @checked true*/
    /** Same impagination as login*/
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));


    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "BasePage.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Login.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Register.php";

    $basePage = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");

    $page = new BasePage($basePage);

    /** Controlla se è stata inserita la password valida.*/
    $validPassword = isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];

    // echo $validPassword;

    $page->addComponent(new Register($_POST['username']?? null,
        $validPassword? $_POST['password'] : null, $_POST['email']?? null));

    echo $page;
?>