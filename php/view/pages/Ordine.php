<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\TagBrowser.php';
    require_once __ROOT__ . '\control\components\Title.php';

    require_once __ROOT__ . '\model\TagElement.php';

    // TODO: Fix the naming and the pathing of files. ( Ordine essendo in un file lotnano)
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("ordine"));

    $page->addComponent(new BreadCrumb(array('Catalogo' => 'catalogo.php', 'Ordine' => '')));
    $page->addComponent(new Title('Ordini', 'Classificazione Uccelli', 'In biologia, ai fini della tassonomia, l ordine è uno 
            dei livelli di classificazione scientifica degli organismi viventi, tanto della zoologia quanto della botanica'));

    $pageNum = isset($_GET['page']) ? $_GET['page'] :  0;

    // TODO: Add to Tagbrowser a previous browisng?
    $page->addComponent(new TagBrowser(TagElement::ordineTags(),'\php\view\pages\ordine.php?page=',
                            $pageNum,10, '\php\view\pages\famiglia.php?id='));

    echo  $page;
?>