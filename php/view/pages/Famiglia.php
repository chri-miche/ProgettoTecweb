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

    isset($_GET['id'])? $res = TagElement::famigliaTags($_GET['id']) : $res = TagElement::famigliaTags();

    if(isset($_GET['id'])){
        $res = TagElement::famigliaTags($_GET['id']);
        $reference = '\Progetto\ProgettoTecweb\php\view\pages\famiglia.php?id='. $_GET['id'].'&page=';
    } else {

        $res = TagElement::famigliaTags();
        $reference = '\Progetto\ProgettoTecweb\php\view\pages\famiglia.php?page=';
    }

    isset($_GET['page']) ? $innerpage = $_GET['page'] : $innerpage = 0;

    $page->addComponent(new TagBrowser($res, $reference, $innerpage, 10,'\Progetto\ProgettoTecweb\php\view\pages\genere.php?id='));

    echo  $page;

?>