<?php

    //TODO: In page building we have multple possible components this means that
    // it's hard to unserstand the h1, h2 etc stuff. Find a way to make a component H1?.
    // (most simple solution is to keep for each important component H1 in the code, maybe a subclass
    // maincontent? To consider.
    class BreadCrumb implements Component {

        private $previous;

        // TODO: Check and make it new. ( non global var dependant)
        public function __construct() {$this->previous = explode('/',$_SERVER["REQUEST_URI"]);}

        // TODO: Check and make it new. ( non global var dependant)
        function build() {

            $ret = "<div class='w3-container w3-green' style='width:100%; height: fit-content'>";
            $ret .= 'Al momento ti trovi in:   ';

            foreach ($this->previous as $prev){ $ret .= " / ". $prev ; }

            return  $ret . "</div>";

        }


    }