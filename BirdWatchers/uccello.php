<?php

require_once __DIR__ . "/standardLayoutIncludes.php";

require_once __DIR__ . "/Application/birdSummary/BirdSummary.php";
require_once __DIR__ . "/Application/databaseObjects/specie/SpecieDAO.php";

try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("uccello"));
    $page->addComponent(new BreadCrumb(array("Catalogo" => "catalogo.php", "Uccello" => "")));

    isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

    try {
        $specie = (new SpecieDAO())->get($id);
        $page->addComponent(new BirdSummary((new SpecieDAO())->get($id)));
    } catch (Throwable $exception) {
        if ($exception->getMessage() == 'Uccello non esistente!') header('Location: catalogo.php');
        $page->addComponent(new BirdError(null, 'Qualcosa con il reperimento della specie selezionata
        è andato storto, prego ritentare o contattare un amministratore del sistema.', 'Oops qualcosa non è andato come doveva', 'catalogo.php', '500', 'Torna al catalogo'));
    }

    echo $page;
} catch (Throwable $exception) {
    header('Location: html/error500.xhtml');
}
