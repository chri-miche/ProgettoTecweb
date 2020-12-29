<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\Login.php';
    require_once __ROOT__ . '\control\components\Report.php';
    require_once __ROOT__ . '\control\components\SearchBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';
    require_once __ROOT__ . '\control\components\browsers\TagBrowser.php';
    require_once __ROOT__ . '\control\components\summaries\BirdSummary.php';

    require_once __ROOT__ . '\model\TagElement.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("uccello"));

    isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

    $page->addComponent(new BirdSummary($id));


    echo $page;

?>