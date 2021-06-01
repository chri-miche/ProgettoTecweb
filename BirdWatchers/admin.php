<?php


define('__IMGROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "res");

require_once "standardLayoutIncludes.php";
require_once "filters.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "admin-welcome-page" . DIRECTORY_SEPARATOR . "AdminWelcomePage.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "AdminPanel.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "SessionUser.php";

$sessionUser = new SessionUser();
try {
    if ($sessionUser->userIdentified() && $sessionUser->getAdmin()) {

        $basePage = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "BaseLayout.xhtml");
        $page = new BasePage($basePage);

        $page->addComponent(new SiteBar("admin"));
        $page->addComponent(new BreadCrumb(array("Amministrazione" => "")));
        try {

            $manage = $_GET["manage"] ?? "";
            $operation = $_GET["operation"] ?? "list";

            $keys = array();

            $data = count($_POST) > 0 ? $_POST : array();

            foreach ($_GET as $key => $value) {
                if ($key != "manage" && $key != "operation") {
                    $keys[$key] = $value;
                }
            }

            if (isset($_FILES['immagine']))
                $data['file_immagine'] = $_FILES['immagine'];

            unset($data['submit']);
            $page->addComponent(new AdminPanel($manage, $operation, $keys, $data));
        } catch (Throwable $error) {
            $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell\'operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
        }
        echo $page->build();
    } else {
        header("Location: login.php");
    }
} catch (Throwable $error) {
    header('Location: html/error500.xhtml');
}
