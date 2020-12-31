<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\SessionUser.php';
    require_once __ROOT__.'\model\UserElement.php';

    // TODO: Move to Control
    // Make it a class?

    $currentUser = new SessionUser();
    $newOldFriend = $_GET['usid'];
    $add = $_GET['add'];

    if($newOldFriend != $currentUser->getUser()->ID) {

        if ($add) $currentUser->getUser()->addFriend($newOldFriend);
        else  $currentUser->getUser()->removeFriend($newOldFriend);

        $currentUser->updateUser();

    }

    $previous = $_GET['previousPath'];
    header('Location: http://localhost:82'. $previous. '?id='. $newOldFriend);

?>