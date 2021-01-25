<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "standardLayoutIncludes.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Login.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "summaries" . DIRECTORY_SEPARATOR . "BirdSummary.php";

    $basePage = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("uccello"));
    $page->addComponent(new BreadCrumb(array("Catalogo" => "Catalogo.php", "Uccello" => "")));

    isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

    $page->addComponent(new BirdSummary($id));


    echo $page;

?>