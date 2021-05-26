<?php
/** Pagina del singolo uccello. */
require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/SessionUser.php";

/** Page specific. */
require_once __DIR__ . "/Application/profile/Profile.php";

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
$sessionUser = new SessionUser();


if($sessionUser->userIdentified()) {

    $userVO = $sessionUser->getUser();

    if (isset($_POST['submit-profile-pic']) && isset($_GET['id']) && $_GET['id'] == $userVO->getId()){

        $name = basename($_FILES["input-file"]["name"]);
        $tmp_name = $_FILES["input-file"]["tmp_name"];

        $rootParent = dirname(__DIR__);

        if ((!is_dir($rootParent . DIRECTORY_SEPARATOR . "res") && !mkdir($rootParent . DIRECTORY_SEPARATOR . "res") || !is_writable($rootParent . DIRECTORY_SEPARATOR . "res"))) {
            throw new Exception("Error creating folder res");
        };
        $proposedPath = DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "$name";

        $tentativi = 0;
        while (file_exists($rootParent . $proposedPath)) {
            $tentativi++;
            $proposedPath = DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . ($tentativi === 0 ? '' : $tentativi) . "$name";
        }

        if (move_uploaded_file($tmp_name, $rootParent . $proposedPath)) {

            $userVO->setImmagine($name);
            $result = (new UserDAO())->save($userVO);

        } else {
            throw new Exception("Non è stato possibile salvare la foto");
        }
    }
}
$page = new BasePage($basePage);

$page->addComponent(new SiteBar('User'));
$page->addComponent(new BreadCrumb(array('Utente' => '')));

$page->addComponent(new Profile($_GET['id'] ?? -1, 'UserPage.php?id='));

echo $page;

?>