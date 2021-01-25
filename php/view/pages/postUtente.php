<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . "standardLayoutIncludes.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "catalogo" . DIRECTORY_SEPARATOR . "GenericBrowser.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "Title.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "PostDAO.php";

    /* Visualizzazione.*/
    // Base component of page layout.
    $basePage = file_get_contents(__ROOT__."" . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $id = $_GET['usid'] ?? header('Location: Home.php');
    $pageUser = (new UserDAO())->get($id);

    $page->addComponent(new SiteBar('PostUtente'));
    
    $page->addComponent(new BreadCrumb(array('Utente' => 'UserPage.php?id='.$pageUser->getId(), 'I Post di: '. $pageUser->getNome()=>'')));

    $page->addComponent(new Title("Post pubblicati",null, "I post che l'utente ha pubblicato sulla piattaforma."));

    $tagPreviewLayout = file_get_contents(__ROOT__. DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "PostCard.xhtml");

    $postList = (new PostDAO())->getOfUtente($pageUser->getId());

    $page->addComponent(new GenericBrowser($postList, $tagPreviewLayout,
        'postUtente.php?usid='.$pageUser->getId()."&", $_GET['page'] ?? 0, 8));

    echo $page;


?>

