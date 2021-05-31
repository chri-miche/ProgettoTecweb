<?php

require_once __DIR__ . "/Application/BasePage.php"; require_once __DIR__ . "/Application/login/Login.php";

try {
    $basePage = file_get_contents("./Application/BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $username = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    $page->addComponent(new Login($username, $password));

    echo $page;
}catch (Throwable $error) { header('Location: html/error500.xhtml');}

?>