<?php


define('__IMGROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "res");

require_once "standardLayoutIncludes.php";
require_once "filters.php";
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

    if (isset($_FILES['immagine'])) {
        $data['file_immagine'] = $_FILES['immagine'];
    }

    $page->addComponent(new BreadCrumb(array("Amministrazione" => "")));

    unset($data['submit']);
    $page->addComponent(new AdminPanel($manage, $operation, $keys, $data));

    echo $page->build();
} else {
    header("Location: login.php");
}

?>