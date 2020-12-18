<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__.'\control\components\SideBar.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\components\VericalComponent.php';

    require_once __ROOT__.'\control\components\Post.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    if(!$page->addComponent(new SearchBar()))
        echo 'Oops something went wrong';

    if(!$page->addComponent(new Post()));
    if(!$page->addComponent(new Post()));

    echo $page;

?>