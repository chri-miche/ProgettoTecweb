<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    /** Page specific. */
    require_once __ROOT__ . '\control\components\summaries\UserPage.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar('User'));
    $page->addComponent(new BreadCrumb(array('Utente' => '')));

    $page->addComponent(new UserPage($_GET['id'] ?? -1, 'UserPage.php?id='));

    echo $page;

?>