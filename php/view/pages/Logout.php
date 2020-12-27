<?php

    session_start();

    unset($_SESSION);
    session_destroy();
    // TODO: Make the location be get?
    header('Location: login.php');

?>