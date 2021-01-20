<?php

define('__ROOT__', dirname(dirname(dirname(__FILE__))));

require_once __ROOT__."/control/components/admin/AdminWelcomePage.php";
require_once __ROOT__."/control/components/admin/AdminPanel.php";
require_once __ROOT__."/control/components/SiteBar.php";
require_once __ROOT__.'\control\BasePage.php';
require_once __ROOT__.'\control\components\BreadCrumb.php';
require_once __ROOT__.'\control\components\search\SearchTab.php';

$basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');
$page = new BasePage($basePage);

$page->addComponent(new SiteBar("search"));

$page->addComponent(new BreadCrumb(array("Ricerca" => "Search.php")));

$keyword = $_POST["search"] ?? "";
$entity = $_POST["entity"] ?? "post";

$page->addComponent(new SearchTab($keyword, $entity));

echo $page->build();

?>