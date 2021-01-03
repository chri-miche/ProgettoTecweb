<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    require_once __ROOT__ . '\control\components\summaries\UserSummary.php';

    require_once __ROOT__ .'\control\components\summaries\UserPage.php';
    require_once __ROOT__ .'\control\components\profile\UserSide.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar('User'));
    $page->addComponent(new BreadCrumb(array('Utente' => '')));

    isset($_GET['id']) ? $id = $_GET['id'] : $id = 1;

    $page->addComponent(new UserPage($id, 'UserPage.php'));

    echo $page;

?>