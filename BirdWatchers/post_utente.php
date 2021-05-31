<?php

require_once "standardLayoutIncludes.php";

require_once __DIR__ . "/Application/genericBrowser/GenericBrowser.php";
require_once __DIR__ . "/Application/title/Title.php";

require_once __DIR__ . "/Application/databaseObjects/user/UserDAO.php";
require_once __DIR__ . "/Application/databaseObjects/post/PostDAO.php";

try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $id = $_GET['usid'] ?? header('Location: index.php');
    $pageUser = (new UserDAO())->get($id);

    // Base layout of any page, should almost always work.
    $page->addComponent(new SiteBar('post_utente'));
    $page->addComponent(new BreadCrumb(array('Utente' => 'user_page.php?id=' . $pageUser->getId(), 'I Post di: ' . $pageUser->getNome() => '')));

    try {
        $page->addComponent(new Title("Post pubblicati", null, "I post che l'utente ha pubblicato sulla piattaforma."));

        $tagPreviewLayout = file_get_contents(__DIR__ . "/Application/feed/postCard/PostCard.xhtml");
        $postList = (new PostDAO())->getOfUtente($pageUser->getId());

        $page->addComponent(new GenericBrowser($postList, $tagPreviewLayout, 'post_utente.php?usid=' .
            $pageUser->getId() . "&", $_GET['page'] ?? 0, 8));
    }catch (Throwable $error){
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
    }
    echo $page;
} catch (Throwable $error) {header('Location: html/error500.xhtml');}

?>

