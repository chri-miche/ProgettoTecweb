<?php
    // Nota carina. In php false viene stampato come vuoto. Sensatissimo.

    require_once __ROOT__.'\model\Element.php';
    class UserElement extends Element {

       protected function loadData() {

            try {

                if($this->ID === null)
                    throw new Exception('Cannot fetch data of element that is not defined yet. 
                                                First define the element.');

                // TODO: Move this to a function? "BaseData"?
                $query =    "SELECT M.isAdmin, U.nome, U.email, U.immagineProfilo FROM Moderatore AS M  RIGHT JOIN 
                                (SELECT * FROM Utente as A 
                                WHERE A.ID =".$this->ID . " LIMIT 1) 
                            AS U ON U.ID = M.UserID LIMIT 1;";

                $userData = $this->getSingleRecord($query);

                $userData['moderator'] = isset($userData['isAdmin']);
                $userData['isAdmin'] = isset($userData['isAdmin']) && $userData['isAdmin'];

                $userData['amici'] = self::getFriendsIds($this->ID);
                $userData['interests'] = self::getInterestsIDs($this->ID);

                return $userData;

            } catch (Exception $e) { echo $e; }

        }

        /** @inheritDoc  */
        public static function checkID($id) {

            try{
                    $query = "SELECT U.ID FROM Utente AS U WHERE U.ID = '$id' LIMIT 1";
                    return !(self::getSingleRecord($query) === null);

            } catch (Exception $e) { return null; }

        }


        // TODO: Rename or change the return type. Why do we the id?
        //      returns the ID of the user if exists, else is false. Null if something goes wrong.
        public static function checkCredentials($email, $password){

            try {

                $query = " SELECT  U.ID FROM  Utente AS U 
                    WHERE U.email ='$email' AND U.password = '$password' LIMIT 1;";
                $res = Element::getSingleRecord($query);

                return (isset($res['ID'])) ? $res['ID'] : false;

            } catch (Exception $e) { return null; }

        }

        /** Overload the function also for ID.
         * @param string $email : Email is unique to a user so we check if it is already set.
         * @return bool : Returns true if he exists, false if not null if error occured. */
        private static function userExists(string $email){
            $query = "SELECT U.ID FROM utente AS U WHERE U.email='". $email. "' LIMIT 1;";
            return isset(self::getSingleRecord($query)['ID']);
        }

        public static function addUser(string $username, string $password, string $email){

            $query = "INSERT INTO utente(nome, email, password, immagineProfilo) VALUE 
                          ('". $username . "',  '" . $email . "', '" . $password ." ', 'defj.pg' );";
            return  (!self::userExists($email)) ? Element::addNew($query) : null;

        }

        /** Returns all friends of the user.*/
        public static function getFriendsIds(int $id){

            try{

                $query = "  SELECT S.seguitoID FROM utente AS U JOIN (  
                                SELECT S.SeguitoID AS seguitoID , S.SeguaceID AS seguaceID
                                FROM seguito AS S WHERE S.SeguaceID =". $id . " 
                            ) AS S ON S.seguaceID = U.ID";

                $ret = [];
                foreach (self::getMultipleRecords($query) as $re)  $ret[] = $re['seguitoID'];

                return $ret;

            } catch (Exception $e ) { return null; }

        }

        public static function getFriends(int $id, int $limit){

            try{

                $query = "  SELECT * FROM utente AS U INNER JOIN(
                                SELECT S.SeguitoID FROM seguito AS S 
                                WHERE S.SeguaceID = ".$id ." LIMIT ". $limit .")
                            AS S ON S.SeguitoID = U.ID ;";

                $result = self::getMultipleRecords($query);

                $return = [];
                foreach ($result as $r)  $return[] = new self(null, $r);

                return $return;


            } catch (Exception $e) { return null; }


        }

        // Why are those static? Mhm. Adding a friend should always be to a UserElement.
        public function addFriend(int $newFriend){
            try{

                $query = " INSERT INTO seguito(SeguitoID, SeguaceID) VALUE (".$newFriend.",". $this->ID.");";
                parent::addNew($query);

            } catch (Exception $e ) { echo'errpore'; return null; }
        }

        public function removeFriend(int $oldFriend){
            try {

                $query = "DELETE FROM seguito  WHERE SeguitoID =". $oldFriend ." AND SeguaceID = ". $this->ID.";";
                parent::removeRecord($query);

            } catch (Exception $e) {echo'errpore'; return null;}

        }


        public static function getInterestsIDs(int $id){

            try {

                $query ="SELECT I.tagID FROM interesse AS I WHERE I.userID = " . $id .";";
                $result =  self::getMultipleRecords($query);

                // TODO: Make it clean, it should be done maybe in Element.
                //  something like singleColumnMultipleRecords?
                $res = array();
                foreach ($result as $item) { $res[] = $item['tagID']; }

                return $res;

            } catch (Exception $e) { return null;}

        }




        /** Get all posts made by the user. */ //TODO: Implement
        public static function getWrittenPosts(int $id){}
        public function getModerator(){ return $this->moderator; }
        public function getAdmin() { return $this->isAdmin; }

    }