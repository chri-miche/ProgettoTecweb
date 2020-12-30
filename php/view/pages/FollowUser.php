<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\SessionUser.php';
    require_once __ROOT__.'\model\UserElement.php';

    // TODO: Move to Control3


    $currentUser = new SessionUser();
    $newOldFriend = $_GET['usid'];
    $add = $_GET['add'];

    if($add)  UserElement::addFriend($currentUser->getUser()->ID, $newOldFriend);
    else UserElement::removeFriend($currentUser->getUser()->ID, $newOldFriend);

    $currentUser->updateUser();

    $previous = $_GET['previousPath'];

    header('Location: http://localhost:82'. $previous. '?id='. $newOldFriend);


?>