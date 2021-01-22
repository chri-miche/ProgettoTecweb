<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));

require_once __ROOT__.'\control\SessionUser.php';
require_once __ROOT__.'\control\BasePage.php';
require_once __ROOT__.'\control\components\SiteBar.php';
require_once __ROOT__.'\control\components\BreadCrumb.php';
require_once __ROOT__.'\model\meta\Persistent.php';
require_once __ROOT__.'\control\components\post\PostForm.php';

$sessionUser = new SessionUser();
$page = new BasePage(file_get_contents(__ROOT__.'\view\BaseLayout.xhtml'));

$page->addComponent(new SiteBar("newpost"));

$page->addComponent(new BreadCrumb(array('Post' => '')));

if (isset($_POST['titolo-post']) && isset($_POST['descrizione-post']) && isset($_POST['user-id'])) {
    $redirectID = '';
    $result = DatabaseAccess::transaction(function () use (&$redirectID) {
        try {
            $content = new Persistent('contenuto', array(
                "ID" => null,
                "UserID" => $_POST['user-id'],
                "isArchived" => 0,
                "content" => $_POST['descrizione-post'],
                "data" => date('Y-m-d'),
            ));
            $content->commitFromProto();
            $content = $content->getUniqueFromProto();
            $id = $content->get('ID');
            if (!isset($id)) {
                throw new Exception("Inserimento non a buon fine");
            }
            $post = new Persistent('post', array(
                'contentID' => $content->get('ID'),
                'title' => $_POST['titolo-post']
            ));
            $post->commitFromProto();

            if (isset($_FILES['immagini-post'])) {
                $folders = array('/res', '/res/PostImages');
                $uploads_dir = '/res/PostImages/User' . $_POST['user-id'];
                foreach ($_FILES["immagini-post"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $rootParent = dirname(__ROOT__);

                        $tmp_name = $_FILES["immagini-post"]["tmp_name"][$key];
                        // basename() may prevent filesystem traversal attacks;
                        // further validation/sanitation of the filename may be appropriate
                        $name = basename($_FILES["immagini-post"]["name"][$key]);

                        $proposedPath = "$uploads_dir/$name";


                        foreach ($folders as $folder) {
                            if (!is_dir($rootParent.$folder) && !mkdir($rootParent.$folder)) {
                                throw new Exception("Error creating folder $uploads_dir");
                            }
                        }
                        if (!is_dir($rootParent.$uploads_dir) && !mkdir($rootParent.$uploads_dir)) {
                            throw new Exception("Error creating folder $uploads_dir");
                        };


                        $tentativi = 0;
                        while (file_exists($rootParent.$proposedPath)) {
                            $tentativi++;
                            $proposedPath = "$uploads_dir/" . ($tentativi === 0 ? '' : $tentativi) . "$name";
                        }

                        if(move_uploaded_file($tmp_name, $rootParent.$proposedPath)) {
                            $immagine = new Persistent('immaginipost', array(
                                'postID' => $id,
                                'percorsoImmagine' => $proposedPath
                            ));
                            $immagine->commitFromProto();
                        } else {
                            throw new Exception("Non è stato possibile salvare le foto");
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            echo "$exception occurred<br />";
            return false;
        }
        $redirectID = $id;
        return true;
    });

    if ($result) {
        header("Location: PostPage.php?id=$redirectID");
        exit;
    } else {
        $page->addComponent(new PostForm($sessionUser, $_POST));
    }
} else {
    $page->addComponent(new PostForm($sessionUser));
}

echo $page;
?>