<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__.'\control\components\Report.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';
    require_once __ROOT__.'\control\components\previews\PostPreview.php';


    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("home"));

    if(!$page->addComponent(new SearchBar())) echo 'Oops something went wrong';
    $page->addComponent(new BreadCrumb(array()));

    echo $page;

?>