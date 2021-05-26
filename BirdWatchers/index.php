<?php

require_once "standardLayoutIncludes.php";
require_once __DIR__ . "/Application/feed/Feed.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

$page = new BasePage($basePage);

$page->addComponent(new SiteBar("home"));

// if(!$page->addComponent(new SearchBar())) echo 'Oops something went wrong';
$page->addComponent(new BreadCrumb(array()));

$feed = $_GET['feed'] ?? 'popularity';
$page->addComponent(new Feed($feed));

echo $page;

?>