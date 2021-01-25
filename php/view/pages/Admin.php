<?php

define('__ROOT__', dirname(dirname(dirname(__FILE__))));


require_once __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "standardLayoutIncludes.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "AdminWelcomePage.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "AdminPanel.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "SessionUser.php";

$sessionUser = new SessionUser();

if ($sessionUser->userIdentified() && $sessionUser->getAdmin()) {
    $basePage = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("admin"));

    $manage = $_GET["manage"] ?? null;
    $operation = $_GET["operation"] ?? "list";
    $keys = array();

    $data = count($_POST) > 0 ? $_POST : array();

    foreach ($_GET as $key => $value) {
        if ($key != "manage" && $key != "operation") {
            $keys[$key] = $value;
        }
    }

    $page->addComponent(new BreadCrumb(array("Amministrazione" => "")));

    $page->addComponent(new AdminPanel($manage, $operation, $keys, $data));

    echo $page->build();
} else {
    header("Location: Login.php");
}



?>