<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__.'\control\BasePage.php';
    require_once __ROOT__.'\control\components\SiteBar.php';
    require_once __ROOT__.'\control\SessionUser.php ';
    require_once __ROOT__.'\control\components\Post.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');

    $sessionUser = new SessionUser();
    $page = new BasePage($basePage);

    $page->addComponent(new SiteBar("postpage"));

    $page->addComponent(new BreadCrumb(array('Post' => '')));

    if (isset($_GET['id']) ) {

        $postID = $_GET['id'];

        if ($sessionUser->userIdentified()) {
            $redirect = true;
            $idUtente = $sessionUser->getUser()->getId();

            if (isset($_GET['comment']) && $_GET['comment'] === '1') {
                DatabaseAccess::transaction(function () use($idUtente, $postID) {
                    try {
                        $content = new Persistent('contenuto', array(
                            'ID' => null,
                            'UserID' => $idUtente,
                            'isArchived' => 0,
                            'content' => $_POST['commento'],
                            'data' => date('Y-m-d')
                        ));
                        $content->commitFromProto();
                        $content = $content->getUniqueFromProto();
                        $comment = new Persistent('commento', array(
                            'postID' => $postID,
                            'contentID' => $content->get('ID')
                        ));
                        $comment->commitFromProto();
                    } catch (Exception $exception) {
                        return false;
                    }
                    return true;
                });

            } elseif (isset($_GET['liked']) && $_GET['liked'] === '1') {
                // remove like

                $like = new Persistent('approvazione', array(
                    'utenteID' => $idUtente,
                    'contentID' => $postID,
                    'likes' => '1'
                ));

                $like->deleteFromProto();

            } elseif (isset($_GET['canLike']) && $_GET['canLike'] === '1') {
                // remove dislike and set like

                DatabaseAccess::transaction(function () use ($idUtente, $postID) {
                    try {
                        $dislike = new Persistent('approvazione', array(
                            'utenteID' => $idUtente,
                            'contentID' => $postID,
                            'likes' => '-1'
                        ));

                        $dislike->deleteFromProto();
                        // elimina se esiste, non va in errore altrimenti

                        $like = new Persistent('approvazione', array(
                            'utenteID' => $idUtente,
                            'contentID' => $postID,
                            'likes' => '1'
                        ));

                        $like->commitFromProto();


                    } catch (Exception $exception) {
                        return false;
                    }
                    return true;
                });

            } elseif (isset($_GET['disliked']) && $_GET['disliked'] === '1') {
                // remove dislike
                $dislike = new Persistent('approvazione', array(
                    'utenteID' => $idUtente,
                    'contentID' => $postID,
                    'likes' => '-1'
                ));

                $dislike->deleteFromProto();

            } elseif (isset($_GET['canDislike']) && $_GET['canDislike'] === '1') {
                // remove like and set dislike
                DatabaseAccess::transaction(function () use ($idUtente, $postID) {
                    try {

                        $like = new Persistent('approvazione', array(
                            'utenteID' => $idUtente,
                            'contentID' => $postID,
                            'likes' => '1'
                        ));

                        $like->deleteFromProto();
                        // elimina se esiste, non va in errore altrimenti

                        $dislike = new Persistent('approvazione', array(
                            'utenteID' => $idUtente,
                            'contentID' => $postID,
                            'likes' => '-1'
                        ));

                        $dislike->commitFromProto();


                    } catch (Exception $exception) {
                        return false;
                    }
                    return true;
                });
            } else {
                $redirect = false;
            }
        }

        if ($redirect) {
            // redirect per pulire le variabili
            header("Location: PostPage.php?id=$postID");
        } else {
            $page->addComponent(new Post($_GET['id'],$sessionUser));
            echo $page;
        }
    } else {
        // TODO error
    }



?>