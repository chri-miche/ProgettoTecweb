<?php

    /*TODO : Scartare, idea carina magari ma non vale la pena faccio prima
                a fare pagina per ogni tipo e mostrare (magari con menu di navigazione sopra
                per cercare direttamente o passare a categorie.**/
    define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__.'\control\components\Report.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    require_once __ROOT__.'\control\components\BrowseElements.php';
    require_once __ROOT__.'\control\components\BrowsePosts.php';
    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\OrdineBrowser.php';

    // TODO: Fix the naming and the pathing of files. ( Ordine essendo in un file lotnano)
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    isset($_GET['page'])?
        $page->addComponent(new OrdineBrowser('\Progetto\ProgettoTecweb\php\view\pages\Catalogo\Ordine.php?page=', $_GET['page'])):
        $page->addComponent(new OrdineBrowser('\Progetto\ProgettoTecweb\php\view\pages\Catalogo\Ordine.php?page=',0));


    echo  $page;
?>