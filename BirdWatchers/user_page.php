<?php

define('__IMGROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "res");

/** Pagina del singolo uccello. */
require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/SessionUser.php";

/** Page specific. */
require_once __DIR__ . "/Application/profile/Profile.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
$sessionUser = new SessionUser();

try {
    if ($sessionUser->userIdentified()) {

        $userVO = $sessionUser->getUser();

        if (isset($_POST['submit-profile-pic']) && isset($_GET['id']) && $_GET['id'] == $userVO->getId()) {

            if ($_FILES["input-file"]["error"] !== 0) {
                throw new Error("L'immagine è troppo grande: si prega di inserire un'immagine più piccola");
            }

            $name = basename($_FILES["input-file"]["name"]);
            $tmp_name = $_FILES["input-file"]["tmp_name"];

            if ((!is_dir(__IMGROOT__) && !mkdir(__IMGROOT__) || !is_writable(__IMGROOT__))) {
                throw new Error("Error creating folder res");
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

            } else {
                throw new Error("Non è stato possibile salvare la foto");
            }
        }
    }
} catch (Error $err) {
    echo $err;
}
$page = new BasePage($basePage);

$page->addComponent(new SiteBar('User'));
$page->addComponent(new BreadCrumb(array('Utente' => '')));

$page->addComponent(new Profile($_GET['id'] ?? -1, 'user_page.php?id='));

echo $page;

?>