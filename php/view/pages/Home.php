<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "standardLayoutIncludes.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "feed" . DIRECTORY_SEPARATOR . "Feed.php";

    $basePage = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("home"));

    // if(!$page->addComponent(new SearchBar())) echo 'Oops something went wrong';
    $page->addComponent(new BreadCrumb(array()));

    $feed = $_GET['feed'] ?? 'popularity';
    $page->addComponent(new Feed($feed));

    echo $page;

?>