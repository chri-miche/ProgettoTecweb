<?php


require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/search/SearchTab.php";
try {

    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $keyword = $_GET["search"] ?? "";
    $entity = $_GET["entity"] ?? "post";


    $currentPage = $_GET['page'] ?? 0;

    $page->addComponent(new SiteBar("search", $keyword));
    $page->addComponent(new BreadCrumb(array("Ricerca" => "")));

    try {
        $page->addComponent(new SearchTab($keyword, $entity, $currentPage));
    } catch (Throwable $error) {
        $page->addComponent(new BirdError(null, 'La ricerca non è potuta andare a buon fine.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Non è stato possibile cercare!', 'index.php', '500'));
    }

    echo $page->build();
} catch (Throwable $error) {
    header('Location: html/error500.xhtml');
}

