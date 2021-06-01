<?php

require_once __DIR__ . "/Application/BasePage.php"; require_once __DIR__ . "/Application/login/Login.php";

try {
    $basePage = file_get_contents("./Application/BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $username = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    try {

        $page->addComponent(new Login($username, $password));
        echo $page;

    } catch (Throwable $error){
        if($error->getMessage() == 'User already authenticated') header('Location: index.php');
        else throw new Exception('Internal serve error');
    }

}catch (Throwable $error) {header('Location: html/error500.xhtml'); }

?>