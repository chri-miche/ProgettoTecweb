<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    /* Pagina di base.*/
    require_once __ROOT__ . '\control\BasePage.php';
    /* Moduli.*/
    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';
    require_once __ROOT__ . '\control\components\catalogo\Catalogo.php';
    /* Elementi dal modello.*/
    require_once __ROOT__ . '\model\BirdElement.php';
    require_once __ROOT__ . '\model\DAO\SpecieDAO.php';

    $basePage = file_get_contents(__ROOT__ . '\view\BaseLayout.xhtml');

    $page = new BasePage($basePage);

    /** Standard navigation for our page.*/
    $page->addComponent(new SiteBar("catalogo"));
    $page->addComponent(new BreadCrumb(array('Catalogo'=> '')));

    $specieDAO = new SpecieDAO();

    $genereDAO = new GenereDAO();
    $famigliaDAO = new FamigliaDAO();
    $ordineDAO = new OrdineDAO();


    /* Dobbiamo trovare: gli ordini, i generi e le famiglie.*/

    $ordineEnabled = isset($_GET['ordineEnabled']); /* What to add is known now.*/
    $famigliaEnabled = isset($_GET['famigliaEnabled']); /* What to add is known now.*/
    $genereEnabled = isset($_GET['genereEnabled']); /* What to add is known now.*/

    /* I valori delle selezioni se esistono */
    $ordineValue =  $_GET['ordineValue'] ?? null;
    $famigliaValue = $_GET['famigliaValue'] ?? null;
    $genereValue =  $_GET['genereValue'] ?? null;


    // Init delle liste.
    $ordineList = array();
    $famigliaList = array();
    $genereList = array();

    if($genereEnabled) {

        $genereList = $genereDAO->getAllFilterBy($famigliaValue, $ordineValue);

        /** Elemento di famiglia della lista di generi attuali.(se selezionato) */
        if($famigliaEnabled && sizeof($genereList) > 0) $famigliaList []= $genereList[0]->getFamigliaVO();

        /** Elemento di ordini della lista di generi attuali. (se selezionato) */
        if($ordineEnabled && sizeof($famigliaList) > 0)
            $ordineList []= $famigliaList[0]->getOrdineVO();

    } else {

        if($famigliaEnabled){

            $famigliaList = $famigliaDAO->getAllFilterBy($ordineValue); // Ricorda può essere null.

            if($ordineEnabled && sizeof($famigliaList) > 0)
                $ordineList []= $famigliaList[0]->getOrdineVO();

        } else {

            if($ordineEnabled) $ordineList = $ordineDAO->getAll();

        }

    }

    $birds = $specieDAO->getAllFilterBy($genereEnabled ? $genereValue : null,$famigliaEnabled? $famigliaValue : null,$ordineEnabled ? $ordineValue : null);


    $page->addComponent(new Catalogo($birds, 'catalogo.php',$_GET['page'] ?? 0, 20, $ordineList,
        $famigliaList, $genereList, $ordineValue, $famigliaValue, $genereValue));
    echo $page;

?>