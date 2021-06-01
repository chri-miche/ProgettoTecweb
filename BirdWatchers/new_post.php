<?php

define('__IMGROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "res");
require_once __DIR__ . "/Application/SessionUser.php";

require_once "standardLayoutIncludes.php";
require_once "filters.php";

require_once __DIR__ . "/Application/post/postForm/PostForm.php";
require_once __DIR__ .
    DIRECTORY_SEPARATOR . "Application" .
    DIRECTORY_SEPARATOR . "databaseObjects" .
    DIRECTORY_SEPARATOR . "post" .
    DIRECTORY_SEPARATOR . "PostVO.php";
require_once __DIR__ .
    DIRECTORY_SEPARATOR . "Application" .
    DIRECTORY_SEPARATOR . "databaseObjects" .
    DIRECTORY_SEPARATOR . "post" .
    DIRECTORY_SEPARATOR . "PostDAO.php";

try {
    $sessionUser = new SessionUser();
    if(!$sessionUser->userIdentified()) header('Location: index.php');

    $page = new BasePage(file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml"));

    $page->addComponent(new SiteBar("new_post"));
    $page->addComponent(new BreadCrumb(array('Post' => '')));
    try {
        if (isset($_POST['titolo-post']) && isset($_POST['descrizione-post']) && isset($_POST['user-id'])) {
            $redirectID = '';
            $result = DatabaseAccess::transaction(function () use (&$redirectID) {
                $immagini = [];
                if (isset($_FILES['immagini-post'])) {

                    $folders = array('/res', '/res/PostImages');
                    $uploads_dir = 'res/PostImages/User' . $_POST['user-id'];

                    foreach ($_FILES["immagini-post"]["error"] as $key => $error) {
                        if ($error == UPLOAD_ERR_OK) {

                            $rootParent = __DIR__;

                            $tmp_name = $_FILES["immagini-post"]["tmp_name"][$key];
                            $name = basename($_FILES["immagini-post"]["name"][$key]);

                            if (!preg_match("/\.(gif|png|jpg)$/", $name))
                                throw new Exception('Il file dato non è un\'immagine.');

                            $proposedPath = "$uploads_dir/$name";

                            echo is_dir($rootParent . $uploads_dir);
                            echo $rootParent . $uploads_dir;

                            foreach ($folders as $folder)
                                if (!is_dir($rootParent . $folder) && !mkdir($rootParent . $folder))
                                    throw new Exception("Errore nella creazione del percorso: $uploads_dir");

                            if (!is_dir($rootParent . "/$uploads_dir") && !mkdir($rootParent . "/$uploads_dir"))
                                throw new Exception("Errore nella creazione del percorso $uploads_dir");


                            $tentativi = 0;
                            while (file_exists($rootParent . $proposedPath)) {
                                $tentativi++;
                                $proposedPath = "$uploads_dir/" . ($tentativi === 0 ? '' : $tentativi) . "$name";
                            }

                            if (move_uploaded_file($tmp_name, $rootParent . "/$proposedPath"))
                                $immagini[] = str_replace('\\', '/', $proposedPath);
                            else
                                throw new Exception("Non è stato possibile salvare le foto");

                        }
                    }
                }
                $postVO = new PostVO(
                    null,
                    false,
                    sanitize_simple_markdown($_POST['descrizione-post']),
                    date('Y-m-d'),
                    sanitize_simple_text($_POST['titolo-post']),
                    0,
                    (new UserDAO())->get($_POST['user-id']),
                    $immagini
                );
                if ((new PostDAO())->save($postVO))
                    $redirectID = $postVO->getId();
                else
                    throw new Exception('Il salvataggio del post ha riscontrato un errore.');
                return true;
            });

            if ($result) {
                header("Location: post_page.php?id=$redirectID");
                exit;
            } else {
                $page->addComponent(new PostForm($sessionUser, $_POST));
            }
        } else {
            $page->addComponent(new PostForm($sessionUser));
        }
    } catch (Throwable $error) {
        $errorMessage = $error->getMessage();
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell\'operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti. In particolare il sistema notifica che: ' . $errorMessage,
            'Attenzione, c\' è stato un errore!', 'new_post.php', '500', 'Torna alla creazione di un Post'));
    }
    echo $page;
} catch (Throwable $error) {
    header('Location: internal_server_error.php?erroStatusCode=500');
}
