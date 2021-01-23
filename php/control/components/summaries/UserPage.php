<?php

    require_once __ROOT__ .'\control\components\profile\UserSide.php';
    require_once __ROOT__ .'\control\components\profile\UserDetails.php';

    require_once __ROOT__ .'\control\components\catalogo\GenericBrowser.php';

    require_once __ROOT__ .'\model\DAO\UserDAO.php';
    require_once __ROOT__ .'\model\DAO\PostDAO.php';


    class UserPage extends Component {

        /** @var UserVO: $user. */
        private $user;
        private $userExists;

        private $selfReference;

        public function __construct(int $id, string $selfReference, string $HTML = null){
            parent::__construct( $HTML ?? file_get_contents(__ROOT__.'\view\modules\user\UserLayout.xhtml'));

            /** Ottenimento dell utente da database, se non esiste la user exists è falsa.*/
            $this->user = (new UserDAO())->get($id);
            $this->userExists =  !is_null($this->user->getId());

            $this->selfReference = $selfReference;

        }

        public function resolveData(){

            $resolvedData = [];
            /** Se utente non esiste si viene reindirizzati a home.*/
            if(!$this->userExists) header('Location: Home.php');


            // TODO: Move user details to top.
            /** Nuovo layout prevede i dettagli in cima. Sotto due pannelli (1 amici, 1 per post)*/
            $resolvedData['{top}'] = (new UserDetails($this->user, $this->selfReference))->returnComponent();

            // Barra a sinistra degli amici.
            $resolvedData['{left}'] = (new UserSide($this->user))->returnComponent();

            // Barra a destra per i post utente.
            $postVOArray = (new PostDAO())->getOfUtente($this->user->getId(),10, 0);
            
            $resolvedData['{right}'] = '';

            $postLayout = file_get_contents(__ROOT__.'\view\modules\user\PostCard.xhtml');
            if(sizeof($postVOArray) > 0){

                $resolvedData['{right}'] .= (new PreviewsPage($postVOArray, $postLayout))->returnComponent();
                $resolvedData['{right}'] .= "<div> <a href='postUtente.php?usid= '".$this->user->getId() ."> 
                                                    Vedi tutti i post dell utente.</a></div>";
            } else {

                $resolvedData['{right}'] .= '<div style="width: 100%; text-align: center"><img src="\res\images\wow-such-empty.png" style="height: 100%"> </img></div>';

            }
            return $resolvedData;

        }


    }