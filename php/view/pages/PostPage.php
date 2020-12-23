<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    // TODO: Make includes files for pages.
    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__.'\control\components\SearchBar.php';

    require_once __ROOT__.'\control\SessionUser.php ';

    require_once __ROOT__.'\control\components\Post.php';

    require_once __ROOT__.'\control\components\BreadCrumb.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $sessionUser = new SessionUser();


    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar());

    if(!$page->addComponent(new SearchBar()))  echo 'Oops something went wrong';
    $page->addComponent(new BreadCrumb());

    (isset($_GET['pid'])) ? $page->addComponent(new Post($_GET['pid'], $sessionUser)) :
                $page->addComponent(new Post(null, $sessionUser));

    if(!$page->addComponent(new Post(null, $sessionUser)));

    echo $page;

?>