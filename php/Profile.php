<?php

    require_once "UserData.php";
    require_once "BuildSideBar.php";


    session_start();

    $userData = null;

    if(isset($_SESSION['User']))
        $userData = unserialize($_SESSION['User']);
    echo '<div id= "content>';

    Builder::buildSideBar($userData);

    echo '<div style="width: 80%; float:right">';
    /** If session is set and has values.*/
    if(isset($userData)){

        $app = $userData->fullData();

        echo $app['nome'] . $app['immagineProfilo'] . '<br></br>';
        if($userData->getModerator()) {
            echo "Utente moderatore <br>" ;
            if ($userData->getAdmin())
                echo "Utente amministratore.";

        }



    } else {

        echo "Effettuare prima il login.";

    }
    echo '</div>'. '</div>';

?>