<?php


    class Page {

        private $HTML;

        public function __construct($content, $hasSideBar = true, $hasSearchBar = true) {

            $HTML = file_get_contents("../xhtml/BaseLayout.xhtml");

            if($hasSideBar)
                $HTML = str_replace("<sideBar />", Builder::buildSideBar(), $HTML);

            if($hasSearchBar)
                $HTML = str_replace("<searchBar />", Builder::buildSearchBar(true), $HTML);


            $HTML = str_replace("<content />", $content->create(), $HTML);


        }

    }