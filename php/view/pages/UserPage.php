<?php
    /** Pagina del singolo uccello. */
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\control\BasePage.php';

    require_once __ROOT__ . '\control\components\SiteBar.php';
    require_once __ROOT__ . '\control\components\BreadCrumb.php';

    /** Page specific. */
    require_once __ROOT__ . '\control\components\summaries\UserPage.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    if (isset($_POST['submit-profile-pic']) && isset($_GET['id'])) {
        $name = basename($_FILES["input-file"]["name"]);
        $tmp_name = basename($_FILES["input-file"]["tmp_name"]);

        $rootParent = dirname(__ROOT__);
        $proposedPath = DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "$name";

        $tentativi = 0;
        while (file_exists($rootParent.$proposedPath)) {
            $tentativi++;
            $proposedPath = "res" . DIRECTORY_SEPARATOR . ($tentativi === 0 ? '' : $tentativi) . "$name";
        }

        if(move_uploaded_file($tmp_name, $rootParent.$proposedPath)) {
            // TODO nome file
        } else {
            throw new Exception("Non è stato possibile salvare le foto");
        }
    }

    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar('User'));
    $page->addComponent(new BreadCrumb(array('Utente' => '')));

    $page->addComponent(new UserPage($_GET['id'] ?? -1, 'UserPage.php?id='));

    echo $page;

?>