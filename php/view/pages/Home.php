<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__.'\control\components\SideBar.php';
    require_once __ROOT__.'\control\components\Report.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->setSideBar(new SideBar());

    if(!$page->addComponent(new SearchBar()))  echo 'Oops something went wrong';
    $page->addComponent(new BreadCrumb(array('Home')));
    $page->addComponent(new Report());

    echo $page;

?>