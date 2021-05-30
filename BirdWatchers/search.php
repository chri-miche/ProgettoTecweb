<?php


require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/search/SearchTab.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
$page = new BasePage($basePage);

$keyword = $_GET["search"] ?? "";
$entity = $_GET["entity"] ?? "post";


$currentPage = $_GET['page']?? 0;

$page->addComponent(new SiteBar("search", $keyword));
$page->addComponent(new BreadCrumb(array("Ricerca" => "")));

$page->addComponent(new SearchTab($keyword, $entity, $currentPage));

echo $page->build();

?>