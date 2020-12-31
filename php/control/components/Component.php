<?php

    /*interface Component {

        const INNER_TAG = '<loggedActions />';

        function build();

        // TODO: Consider.
        // Array of parameters that create a  new istance of the component?.
        //function update($params);

        /*function print(); -> If the component was already built you return the built value, else returns the build process.
         * */
    /*}*/

    abstract class Component {

        private $HTML;

        private $builtHTML; private $built;



        public function __construct(string $HTML){

            $this->HTML = $HTML;
            $this->built = false;

        }

        abstract public function build();

        public function returnComponent(){

            if(!$this->built) {

                $this->builtHTML = $this->build();
                $this->built = true;
            }

            return $this->builtHTML;

        }

        // TODO: Set modified (so that build can be false).

        protected function baseLayout(){ return $this->HTML; }

        public function __toString(){ return $this->returnComponent(); }


    }

?>