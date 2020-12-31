<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\summaries\UserSummary.php';

    require_once __ROOT__ .'\control\components\profile\UserSide.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar('User'));

    isset($_GET['id']) ? $id = $_GET['id'] : $id = 1;


    $page->addComponent(new UserSummary($id, $_SERVER['PHP_SELF']));

    $page->addComponent(new UserSide($id));

    echo $page;

?>