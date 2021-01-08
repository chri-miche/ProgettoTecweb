<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\Browser.php';
    require_once __ROOT__ . '\control\components\ordine\OrdiniBrowser.php';
    require_once __ROOT__ . '\control\components\Title.php';

    require_once __ROOT__ . '\model\TagElement.php';


    // TODO: Fix the naming and the pathing of files. ( Ordine essendo in un file lotnano)
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("ordine"));

    $page->addComponent(new BreadCrumb(array('Catalogo' => 'catalogo.php', 'Ordine' => '')));

    $pageNum = isset($_GET['page']) ? $_GET['page'] :  0;

    // TODO: Avoid creating all tags, just the ones we care about. Can't be done, we don't know how many tags we hace if we do.
    $page->addComponent(new OrdiniBrowser($pageNum));
    echo $page;
?>