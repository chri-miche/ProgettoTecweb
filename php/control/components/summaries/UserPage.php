<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "profile" . DIRECTORY_SEPARATOR . "UserDetails.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "catalogo" . DIRECTORY_SEPARATOR . "GenericBrowser.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "UserDAO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "PostDAO.php";


    class UserPage extends Component {

        /** @var UserVO: $user. */
        private $user;
        private $userExists;

        private $selfReference;

        public function __construct(int $id, string $selfReference, string $HTML = null){
            parent::__construct( $HTML ?? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "UserLayout.xhtml"));

            /** Ottenimento dell utente da database, se non esiste la user exists è falsa.*/
            $this->user = (new UserDAO())->get($id);
            $this->userExists =  !is_null($this->user->getId());

            $this->selfReference = $selfReference;

        }

        public function resolveData(){

            $resolvedData = [];
            /** Se utente non esiste si viene reindirizzati a home.*/
            if(!$this->userExists) header('Location: index.php');

            /** Nuovo layout prevede i dettagli in cima. Sotto due pannelli (1 amici, 1 per post)*/
            $resolvedData['{top}'] = (new UserDetails($this->user, $this->selfReference))->returnComponent();

            // Barra a destra per i post utente.
            $postVOArray = (new PostDAO())->getOfUtente($this->user->getId(),8, 0);

            $resolvedData['{right}'] = '';

            $postLayout = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "PostCard.xhtml");
            if(sizeof($postVOArray) > 0){

                $resolvedData['{right}'] .= (new PreviewsPage($postVOArray, $postLayout))->returnComponent();
                $resolvedData['{right}'] .= "<a href='postUtente.php?usid=".$this->user->getId() ."'>Vedi tutti i post dell'utente.</a>";
            } else {

                $resolvedData['{right}'] .= '<img src="\res\images\wow-such-empty.png" alt="Questo utente non ha ancora postato nulla" />';

            }
            return $resolvedData;

        }


    }