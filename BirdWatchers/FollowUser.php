<?php


require_once __DIR__ . "/Application/SessionUser.php";
require_once __DIR__ . "/Application/databaseObjects/user/UserDAO.php";

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