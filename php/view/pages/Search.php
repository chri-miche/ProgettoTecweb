<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "standardLayoutIncludes.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "search" . DIRECTORY_SEPARATOR . "SearchTab.php";

    $basePage = file_get_contents(__ROOT__."" . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $keyword = $_POST["search"] ?? "";
    $entity = $_GET["entity"] ?? "post";


    $currentPage = $_GET['page']?? 0;

    $page->addComponent(new SiteBar("search", $keyword));
    $page->addComponent(new BreadCrumb(array("Ricerca" => "")));

    $page->addComponent(new SearchTab($keyword, $entity, $currentPage));

    echo $page->build();

?>