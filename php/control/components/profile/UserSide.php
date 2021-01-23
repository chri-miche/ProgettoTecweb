<?php

    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__ .'\model\DAO\UserDAO.php';

    require_once __ROOT__.'\control\components\generics\LinkRow.php';
    /* Diventa semplicemente la lista di amici.*/
    class UserSide extends Component {

        private $usid;

        private $friendsArray;
        private $userPageReference;

        private $friendsDisplayLimit;


        public function __construct(UserVO $user, int $maxFriends = 20, string $reference = 'UserPage.php', string $HTML = null) {

            parent::__construct($HTML ?? file_get_contents(__ROOT__.'\view\modules\user\UserSide.xhtml'));

            /** Ottenimento dati utente e suoi amici.*/

            $this->usid = $user->getId();
            $this->friendsArray = (new UserDAO())->getFriends($user);

            $this->friendsDisplayLimit = $maxFriends;
            $this->userPageReference = $reference;

        }

        public function resolveData() {

            $resolveData = [];

            /** Ogni proprietà di utente attuale.*/
            $friendsList = ''; // Costruzione della lista di amici da presentare.


            for($i = 0; $i < $this->friendsDisplayLimit && $i < sizeof($this->friendsArray); $i++) {
                /* Riferimento alla pagina dell amico.*/
                $friendReference = $this->userPageReference . "?id=" . $this->friendsArray[$i]->getId();

                /** Link di accesso ad una pagina di un amico. */
                $friendsList .= (new LinkRow($friendReference, $this->friendsArray[$i]))->build();
            }

            $resolveData['{users}'] = $friendsList;

            /** Se ho più di quanto posso visualizzare mandami alla pagina: Tutti gli utenti seguiti.*/
            $resolveData['{friendsReference}'] = sizeof($this->friendsArray) >= $this->friendsDisplayLimit
                ? "<a href='UserFriends.php?id=$this->usid'> Vedi altri amici </a>" : null;

            return $resolveData;


        }


    }