<?php

    require_once __ROOT__ ."/model/UserData.php";

    class Builder {

        /** Classe non istanziabile.*/
        private function __construct() {}

        private static function currentUser(){

            $element = null;

            if(isset($_SESSION['User']))
                $element = unserialize($_SESSION['User']);

            return $element;

        }

        static public function buildSideBar(){
            /**Costruzione preliminare tutto quello che serve.*/

            $element = self::currentUser();

            $HTMLdoc = file_get_contents("../xhtml/SideBar.xhtml");
            $personalSideElements = "";

            if(isset($element)){
                /** Add user options in the side bar and visualization. */

                $personalSideElements .=
                    "<ul>
                        <li> Utente </li>
                        <li><a href='Logout.php'>Logout</a></li>
                    </ul>";

                if($element->getModerator()) {
                    /** Add moderator in the side bar and visulazioation of it.*/
                    if ($element->getAdmin()) {
                        /** Get option to open the admin control panel.*/

                    }
                }
            } else {

                $personalSideElements .=
                    "<li><a href='Login.php'>Login</a></li>
                        </ul>";

            }

            return str_replace("<personalSidebar/>", $personalSideElements, $HTMLdoc);

        }

        static public function buildSearchBar($val){

            if($val)

                return "
                <div id= 'search-bar' class= 'primary-color' >
                    <form id = 'search-form' method='post' name='search'>
                        <label for ='search-input'>Cerca</label>
                        <input id= 'search-input' alt= 'Cerca' ></input>
                        <input type= 'submit' name='Submit' value ='search'></input>
                    </form>
                </div>"
                    ;
            else
                return "";


        }

        static public function buildProfileContent(){

            $element = self::currentUser();

            if(isset($element)){

                return $element->getId();

            }

            else return "Non sei identificato, eseugi l'accesso.";


        }

    }


?>