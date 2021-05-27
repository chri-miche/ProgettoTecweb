<?php

define('__ROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR . "php");

    require_once "filters.php";
    require_once "standardLayoutIncludes.php";
    require_once __DIR__ . "/Application/SessionUser.php ";
    require_once __DIR__ . "/Application/post/Post.php";

    require_once __DIR__ . "/Application/databaseObjects/post/PostDAO.php";

    $basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");

    $sessionUser = new SessionUser();
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("post_page"));
    $page->addComponent(new BreadCrumb(array('Post' => '')));

    $postVO = (new PostDAO())->get($_GET['id']?? -1);

    /** Se il post è valido.*/
    if($postVO->getId()){

        if($sessionUser->userIdentified()){
            if(isset($_GET['comment']) && $_GET['comment']) {
                // $comment = strip_tags($_POST["commento"]);
                // $comment = htmlentities($comment);

                $comment = sanitize_simple_markdown($_POST["commento"]);

                $commentVO = new CommentoVO(null,false, $comment, null, $postVO, $sessionUser->getUser());
                $transaction = (new CommentoDAO())->save($commentVO);

                echo $transaction;

            } else if(isset($_GET['liked']) && $_GET['liked']){

                $transaction = (new PostDAO())->like($sessionUser->getUser(), $postVO);

            } else if(isset($_GET['disliked']) && $_GET['disliked']) {

                $transaction = (new PostDAO())->dislike($sessionUser->getUser(), $postVO);

            }
        }

        if(isset($transaction) && !$transaction)
            header("Location: post_page.php?id=".$postVO->getId());

        $page->addComponent(new Post($postVO, $sessionUser));

    }

    echo $page;



?>