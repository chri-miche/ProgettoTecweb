<?php

/** Mi faccio dare da dove arrivo e metto un redirect se arrivo da un momento di non errore (così utente non può entrare.*/
require_once __DIR__ . "/Application/BasePage.php"; require_once __DIR__ . "/Application/error/BirdError.php";
$basePage = file_get_contents("./Application/BaseLayout.xhtml");

$page = new BasePage($basePage);

/** Get from post the error status. Else you redirect to home.*/
$page->addComponent(new BirdError());
