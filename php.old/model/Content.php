<?php

    interface Content {

         public function create();


    }


    class SearchContent implements Content{

        private $tagIDs;

        private $tagData;

        public function __construct() {

            if(!session_status())  session_start();

            if(isset($_SESSION['SearchTags'])) {

                $tags = unserialize($_SESSION['SearchTags']);
                unset($_SESSION['SearchTags']);

                foreach ($tags as $tag)
                    array_push($this->tagIDs, tag['ID']);

             }

        }

        public function create() {
            // TODO: Implement create() method.


        }

        public function findElements(){


        }

    }