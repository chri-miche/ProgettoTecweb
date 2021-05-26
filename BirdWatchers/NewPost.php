<?php


require_once __DIR__ . "/Application/SessionUser.php";

require_once "standardLayoutIncludes.php";
require_once "filters.php";

require_once __DIR__ . "/Application/databaseObjects/meta/Persistent.php";
require_once __DIR__ . "/Application/post/postForm/PostForm.php";

$sessionUser = new SessionUser();
$page = new BasePage(file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml"));

$page->addComponent(new SiteBar("newpost"));

$page->addComponent(new BreadCrumb(array('Post' => '')));

if (isset($_POST['titolo-post']) && isset($_POST['descrizione-post']) && isset($_POST['user-id'])) {
    $redirectID = '';
    $result = DatabaseAccess::transaction(function () use (&$redirectID) {
        try {
            $content = new Persistent('contenuto', array(
                "id" => null,
                "user_id" => $_POST['user-id'],
                "is_archived" => 0,
                "content" => sanitize_simple_markdown($_POST['descrizione-post']),
                "data" => date('Y-m-d'),
            ));
            $content->commitFromProto();
            $content = $content->getUniqueFromProto();

            $id = $content->get('id');

            if (!isset($id)) {
                throw new Exception("Inserimento non a buon fine");
            }
            $post = new Persistent('post', array(
                'content_id' => $content->get('id'),
                'title' => sanitize_simple_text($_POST['titolo-post'])
            ));
            $post->commitFromProto();

            if (isset($_FILES['immagini-post'])) {

                $folders = array('/res', '/res/PostImages');
                $uploads_dir = '/res/PostImages/User' . $_POST['user-id'];

                foreach ($_FILES["immagini-post"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {

                        $rootParent = __DIR__;

                        $tmp_name = $_FILES["immagini-post"]["tmp_name"][$key];
                        // basename() may prevent filesystem traversal attacks;
                        // further validation/sanitation of the filename may be appropriate
                        $name = basename($_FILES["immagini-post"]["name"][$key]);

                        $proposedPath = "$uploads_dir/$name";

                        echo $proposedPath;

                        foreach ($folders as $folder) {
                            if (!is_dir($rootParent.$folder) && !mkdir($rootParent.$folder)) {
                                echo 'Error creating folder';
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
                                'post_id' => $id,
                                'percorso_immagine' => str_replace('\\', '/', $proposedPath)
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