<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SideBar.php';
    require_once __ROOT__.'\control\components\SearchBar.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    if(!$page->addModule(new SideBar()))
        echo 'Ooops something went wrong';

    if(!$page->addModule(new SearchBar())){

        //echo 'Ooops something went wrong';
        /** Error display module apposta? */
    }

    echo $page;

?>