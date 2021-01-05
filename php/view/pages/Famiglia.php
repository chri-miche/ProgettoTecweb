<?php

    // TODO: Fix these and all the others.
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\Report.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\Browser.php';
    require_once __ROOT__ . '\control\components\Title.php';

    require_once __ROOT__ . '\model\TagElement.php';


    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    // TODO: Consider if title is component
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("famiglia"));
    $page->addComponent(new SearchBar());

    $page->addComponent(new Title('Famiglia', 'Classificazione Uccelli', 'Classificazione di bla bla.'));

    if(isset($_GET['id'])){

        $res = TagElement::famigliaTags($_GET['id']);
        $reference = '\php\view\pages\famiglia.php?id='. $_GET['id'].'&page=';

    } else {

        $res = TagElement::famigliaTags();
        $reference = '\php\view\pages\famiglia.php?page=';

    }

    $innerPage = isset($_GET['page']) ? $_GET['page'] : 0;

    $page->addComponent(new Browser($res, new TagPreview(new TagElement(0)), $reference,
        '\php\view\pages\genere.php?id=', $innerPage,10));

    echo  $page;

?>