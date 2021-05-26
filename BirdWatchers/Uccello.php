<?php


require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/login/Login.php";

require_once __DIR__ . "/Application/birdSummary/BirdSummary.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

$page = new BasePage($basePage);

$page->addComponent(new SiteBar("uccello"));
$page->addComponent(new BreadCrumb(array("Catalogo" => "Catalogo.php", "Uccello" => "")));

isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

$page->addComponent(new BirdSummary($id));


echo $page;

?>