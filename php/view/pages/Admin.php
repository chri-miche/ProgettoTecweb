<?php

define('__ROOT__', dirname(dirname(dirname(__FILE__))));

// TODO directory separator?

require_once __ROOT__."/control/components/admin/AdminWelcomePage.php";
require_once __ROOT__."/control/components/admin/AdminPanel.php";
require_once __ROOT__."/control/components/SiteBar.php";
require_once __ROOT__.'\control\BasePage.php';
require_once __ROOT__.'\control\SessionUser.php';
require_once __ROOT__.'\control\components\BreadCrumb.php';

$sessionUser = new SessionUser();

if ($sessionUser->userIdentified() && $sessionUser->getAdmin()) {
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');
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