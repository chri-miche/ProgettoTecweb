<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\Report.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\BrowseElements.php';
    require_once __ROOT__ . '\control\components\BrowsePosts.php';
    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\TagBrowser.php';
    require_once __ROOT__ . '\control\components\Title.php';

    require_once __ROOT__ . '\model\TagElement.php';
    // TODO: Fix the naming and the pathing of files. ( Ordine essendo in un file lotnano)
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    // TODO: Consider if title is component
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar());
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

    $page->addComponent(new TagBrowser($res, $reference, $innerPage,
        10,'\php\view\pages\genere.php?id='));

    echo  $page;

?>