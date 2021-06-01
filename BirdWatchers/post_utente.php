<?php

require_once "standardLayoutIncludes.php";

require_once __DIR__ . "/Application/genericBrowser/GenericBrowser.php";

require_once __DIR__ . "/Application/databaseObjects/user/UserDAO.php";
require_once __DIR__ . "/Application/databaseObjects/post/PostDAO.php";

try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
    $page = new BasePage($basePage);

    $id = $_GET['usid'] ?? header('Location: index.php');
    $pageUser = (new UserDAO())->get($id);

    // Base layout of any page, should almost always work.
    $page->addComponent(new SiteBar('post_utente'));
    $page->addComponent(new BreadCrumb(array("Utente $pageUser->nome" => 'user_page.php?id=' . $pageUser->getId(), 'Post' => '')));

    try {
        $tagPreviewLayout = file_get_contents(__DIR__ . "/Application/feed/postCard/PostCard.xhtml");
        $postList = (new PostDAO())->getOfUtente($pageUser->getId());

        $page->addComponent(new GenericBrowser($postList, $tagPreviewLayout, 'post_utente.php?usid=' .
            $pageUser->getId() . "&", $_GET['page'] ?? 0, 8));
    } catch (Throwable $error) {
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
    }
    echo $page;
} catch (Throwable $error) {
    header('Location: internal_server_error.php?erroStatusCode=500');
}


