<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';
    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';
    require_once __ROOT__ . '\control\components\browsers\TagBrowser.php';
    require_once __ROOT__ . '\model\TagElement.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar());
    $page->addComponent(new SearchBar());

    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $reference = isset($id) ? '\php\view\pages\genere.php?id='. $id .'&page=' : '\php\view\pages\genere.php?page=';
    $innerPage = isset($_GET['page']) ? $_GET['page'] :  0;

    $page->addComponent(new TagBrowser(TagElement::genereTags($id), $reference, $innerPage, 10, '\php\view\pages\specie.php?id='));

    echo  $page;

?>