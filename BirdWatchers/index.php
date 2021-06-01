<?php

require_once "standardLayoutIncludes.php";
require_once __DIR__ . "/Application/feed/Feed.php";

/** To know if we have to display friends tab.*/
require_once __DIR__ . "/Application/SessionUser.php";
require_once __DIR__ . "/Application/error/BirdError.php";

try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("index"));
    $page->addComponent(new BreadCrumb(array()));


    $currentUser = new SessionUser();

    $feed = $_GET['feed'] ?? 'popularity';
    try {
        $page->addComponent($feed == 'friends' && !$currentUser->userIdentified() ?
            new BirdError(null, 'You have to be logged in to see your friends.',
                'You cannot do that!', 'index.php', '301') : new Feed($feed, $currentUser));
    } catch (Throwable $err) {
        $page->addComponent(new BirdError(null, 'Ci dispiace tanto ma deve essere successo qualcosa di inaspettato, 
    la preghiamo di ritentare l operazione o di contattare un amministratore.', 'Oops something went wrong!', 'index.php', '0'));
    }
    echo $page;
} catch (Throwable $error) {
    header('Location: internal_server_error.php?erroStatusCode=500');
}
