<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';
    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';
    require_once __ROOT__ . '\control\components\browsers\TagBrowser.php';
    require_once __ROOT__ . '\model\TagElement.php';


    require_once __ROOT__ . '\control\components\browsers\Browser.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("genere"));
    $page->addComponent(new SearchBar());

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    $reference = isset($id) ? '\php\view\pages\genere.php?id='. $id .'&page=' : '\php\view\pages\genere.php?page=';
    $innerPage = isset($_GET['page']) ? $_GET['page'] :  0;

    $page->addComponent(new Browser(TagElement::genereTags($id),new TagPreview(new TagElement(0)), $reference,
        '\php\view\pages\specie.php?id=',$innerPage, 10, ));

    echo  $page;

?>