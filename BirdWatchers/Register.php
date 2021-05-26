<?php
/** @checked true*/
/** Same impagination as login*/
require_once __DIR__ . "/Application/BasePage.php";
require_once __DIR__ . "/Application/login/Login.php";
require_once __DIR__ . "/Application/register/Register.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

$page = new BasePage($basePage);

/** Controlla se è stata inserita la password valida.*/
$validPassword = isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];

// echo $validPassword;

$page->addComponent(new Register($_POST['username']?? null,
    $validPassword? $_POST['password'] : null, $_POST['email']?? null));

echo $page;
?>