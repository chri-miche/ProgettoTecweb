<?php
define('__ROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "php");

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "SessionUser.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";

    $userDAO = new UserDAO();

    $currentUserVO = (new SessionUser())->getUser();

    /** L utente da aggiungere.*/
    $friendId = $_POST['usid'] ?? null;

    // echo $friendId;
    if(is_null($friendId))
        header("Location: index.php");

    $friendVO = $userDAO->get($friendId);

    /** Adds the user to friends if he is not in it, else he removes from there*/
    $userDAO->follow($currentUserVO, $friendVO);

    $previous = $_POST['previousPath'];
    header("Location: $previous$friendId");

?>