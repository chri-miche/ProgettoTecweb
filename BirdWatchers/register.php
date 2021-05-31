<?php
/** @checked true*/
/** Same impagination as login*/
require_once __DIR__ . "/Application/BasePage.php";
require_once __DIR__ . "/Application/login/Login.php";
require_once __DIR__ . "/Application/register/Register.php";
try{
    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $page = new BasePage($basePage);

    /** Controlla se è stata inserita la password valida.*/
    $validPassword = isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];
    try {
        $page->addComponent(new Register($_POST['username'] ?? null,
            $validPassword ? $_POST['password'] : null, $_POST['email'] ?? null));
    } catch (Throwable $exception){
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
    } echo $page;

}catch (Throwable $exception) { header('Location: html/error500.xhtml');}
?>

