<?php

    require_once "UserData.php";

    class Builder {

        /** Classe non istanziabile.*/
        private function __construct() {}


        static public function buildSideBar($element){
            /**Costruzione preliminare tutto quello che serve.*/

            echo '<div style="width: 20%; float:left">';

            if(isset($element)){
                /** Add user options in the side bar and visualization. */
                echo 'Bottone 1 <br></br>';
                if($element->getModerator()) {
                    /** Add moderator in the side bar and visulazioation of it.*/
                    echo 'Bottone 2 <br></br>';
                    if ($element->getAdmin()) {
                        /** Get option to open the admin control panel.*/

                    }
                }
            } else {

               /** Not logged user interaction. */

            }

            echo '</div>';
        }
    }


?>