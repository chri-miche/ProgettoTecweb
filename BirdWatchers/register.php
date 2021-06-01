<?php

require_once __DIR__ . "/Application/BasePage.php";
require_once __DIR__ . "/Application/login/Login.php";
require_once __DIR__ . "/Application/register/Register.php";

require_once __DIR__ . "/Application/error/BirdError.php";
try {

    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $page = new BasePage($basePage);
    /** Controlla se è stata inserita la password valida.*/
    $validPassword = isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];

    try {
        $registerComponent = new Register($_POST['username'] ?? null, $validPassword ? $_POST['password'] : null, $_POST['email'] ?? null);
        if ($registerComponent->registered()) header('Location: index.php');
        $page->addComponent($registerComponent);

    } catch (Throwable $exception) {
        if ($exception->getMessage() == 'User già autenticato') header('Location: index.php');
        $page->addComponent(new BirdError(null, 'Qualcosa non è andato a buon fine nell operazione.
            Ritentare o contattare un amministratore per eventuali chiarimenti.', 'Attenzione, c\' è stato un errore!', 'index.php', '500'));
    }

    echo $page;


} catch (Throwable $exception) { //header('Location: html/error500.xhtml');}
    echo $exception;
}