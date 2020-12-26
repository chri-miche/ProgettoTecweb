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

    require_once __ROOT__ . '\model\TagElement.php';
    // TODO: Fix the naming and the pathing of files. ( Ordine essendo in un file lotnano)
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar());
    $page->addComponent(new SearchBar());

    if(isset($_GET['id'])){

        $res = TagElement::specieTags($_GET['id']);
        $reference = '\php\view\pages\specie.php?id='. $_GET['id'].'&page=';

    } else {

        $res = TagElement::specieTags();
        $reference = '\php\view\pages\specie.php?page=';

    }

    $innerPage = isset($_GET['page']) ? $_GET['page'] :  0;

    $page->addComponent(new TagBrowser($res, $reference, $innerPage, 10, '\php\view\pages\uccello.php?id='));

    echo  $page;

?>