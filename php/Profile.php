<?php

    define('__ROOT__', dirname(__FILE__));
    require_once (__ROOT__ . "/control/Builder.php");

    session_start();
    $HTMLpage = file_get_contents("../xhtml/BaseLayout.xhtml");


    if(!isset($_POST['Submit'])){
        $_SESSION['SearchTags'] = /**/ null;
        /** Open result page.  ?**/

        $HTMLpage = str_replace("<sideBar />", Builder::buildSideBar(), $HTMLpage);

        $HTMLpage = str_replace("<searchBar />", Builder::buildSearchBar(true), $HTMLpage);

        $HTMLpage = str_replace("<content />", Builder::buildProfileContent(), $HTMLpage);

        echo $HTMLpage;

    } else if(isset($_POST['Submit'])) {

        $HTMLpage = str_replace("<sideBar />", Builder::buildSideBar(), $HTMLpage);

        $HTMLpage = str_replace("<searchBar />", Builder::buildSearchBar(true), $HTMLpage);

        $HTMLpage .= 'rubyrubyruby';

        echo $HTMLpage;

    }


?>