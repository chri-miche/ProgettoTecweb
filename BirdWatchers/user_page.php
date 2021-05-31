<?php

define('__IMGROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "res");

/** Pagina del singolo uccello. */
require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/SessionUser.php";

/** Page specific. */
require_once __DIR__ . "/Application/profile/Profile.php";
try {
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
    $sessionUser = new SessionUser();

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar('User'));
    $page->addComponent(new BreadCrumb(array('Utente' => '')));

    try {
        if ($sessionUser->userIdentified()) {

            $userVO = $sessionUser->getUser();
            if (isset($_POST['submit-profile-pic']) && isset($_GET['id']) && $_GET['id'] == $userVO->getId()) {

                $name = basename($_FILES["input-file"]["name"]);
                if(!preg_match("/\.(gif|png|jpg)$/", $name))
                    throw new Exception('Given file was not image');

                $tmp_name = $_FILES["input-file"]["tmp_name"];

                if ((!is_dir(__IMGROOT__) && !mkdir(__IMGROOT__) || !is_writable(__IMGROOT__))) {
                    throw new Exception("Error creating folder res");
                };

                $proposedPath = DIRECTORY_SEPARATOR . $name;

                $tentativi = 0;
                while (file_exists(__IMGROOT__ . $proposedPath)) {
                    $tentativi++;
                    $proposedPath = DIRECTORY_SEPARATOR . $tentativi . $name;
                }

                if (move_uploaded_file($tmp_name, __IMGROOT__ . $proposedPath)) {

                    $userVO->setImmagine(str_replace('\\', '/', "res" . $proposedPath));
                    $result = (new UserDAO())->save($userVO);

                } else
                    throw new Exception("Non è stato possibile salvare la foto");
            }
        }

        $page->addComponent(new Profile($_GET['id'] ?? -1, 'user_page.php?id='));
    } catch (Throwable $err) {
        $errorMessage = $err->getMessage();
        $page->addComponent(new BirdError(null, "Nel caricare l immagine si è verificato un errore. In particolare si è verificato: $errorMessage",
            'Siamo molto imbarazzati ma qualcosa è andato storto', 'index.php', '500'));
    }

    echo $page;

} catch (Throwable $exception){header('Location: html/error500.xhtml');}
?>