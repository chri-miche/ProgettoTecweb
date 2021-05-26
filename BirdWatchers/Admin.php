<?php

require_once "standardLayoutIncludes.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "admin-welcome-page" . DIRECTORY_SEPARATOR . "AdminWelcomePage.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "AdminPanel.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "SessionUser.php";

$sessionUser = new SessionUser();

if ($sessionUser->userIdentified() && $sessionUser->getAdmin()) {
    $basePage = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("admin"));

    $manage = $_GET["manage"] ?? "";
    $operation = $_GET["operation"] ?? "list";
    $keys = array();

    $data = count($_POST) > 0 ? $_POST : array();

    foreach ($_GET as $key => $value) {
        if ($key != "manage" && $key != "operation") {
            $keys[$key] = $value;
        }
    }

    $page->addComponent(new BreadCrumb(array("Amministrazione" => "")));

    unset($data['submit']);
    $page->addComponent(new AdminPanel($manage, $operation, $keys, $data));

    echo $page->build();
} else {
    header("Location: Login.php");
}

?>