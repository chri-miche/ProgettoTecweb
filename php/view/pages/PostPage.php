<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__.'\control\components\SiteBar.php';
    require_once __ROOT__.'\control\SessionUser.php ';
    require_once __ROOT__.'\control\components\Post.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    require_once __ROOT__.'\model\DAO\PostDAO.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $sessionUser = new SessionUser();
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("postpage"));
    $page->addComponent(new BreadCrumb(array('Post' => '')));

    $postVO = (new PostDAO())->get($_GET['id']?? -1);

    /** Se il post è valido.*/
    if($postVO->getId()){

        if($sessionUser->userIdentified()){
            if(isset($_GET['comment']) && $_GET['comment']) {

                $commentVO = new CommentoVO(null,false, $_POST["commento"], null, $postVO, $sessionUser->getUser());
                $transaction = (new CommentoDAO())->save($commentVO);

                echo $transaction;

            } else if(isset($_GET['liked']) && $_GET['liked']){

                $transaction = (new PostDAO())->like($sessionUser->getUser(), $postVO);

            } else if(isset($_GET['disliked']) && $_GET['disliked']) {

                $transaction = (new PostDAO())->dislike($sessionUser->getUser(), $postVO);

            }
        }

        if(isset($transaction) && !$transaction)
            header("Location: PostPage.php?id=".$postVO->getId());

        $page->addComponent(new Post($postVO, $sessionUser));

    }

    echo $page;



?>